<?php

namespace App\Services;

use App\Models\Product;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Exceptions\PrismRateLimitedException;

class AIService
{
    public function askAssistant(string $question)
    {
        set_time_limit(180); // Increase max execution time for this request
        // ... (cache block same) ...
        // Cache the product context for 60 minutes to reduce DB queries
        $productsContext = Cache::remember('ai_product_context', 3600, function () {
            return Product::select('name', 'price', 'description')
                ->get()
                ->map(function ($product) {
                    return "- {$product->name} ({$product->price} SAR): {$product->description}";
                })->join("\n");
        });

        $generalInfo = config('honey_knowledge.general_info');
        $policies = config('honey_knowledge.policies');

        $systemPrompt = "You are a 'Saudi Honey Expert' working for Manahil Wadi Al-Jarfah.
        Your goal is to help customers choose honey, explain benefits, and check prices with a friendly, welcoming Saudi tone.

        Persona & Tone:
        - Use phrases like 'يا هلا', 'أبشر', 'حياك الله'.
        - Be warm, knowledgeable, and trustworthy.

        Recommendation Logic:
        - If asked about 'Colon' (القولون) or stomach issues -> Recommend 'Talh' (الطلح) immediately.
        - If asked about 'Rare' or 'Luxury' honey -> Recommend 'Majra' (المجرة) and mention its creamy texture and rarity.
        - If asked about 'Immunity' -> Recommend 'Sidr' (السدر).
        
        Instructions:
        1. For PRICES and PRODUCTS: Use ONLY the provided database context.
        2. For BENEFITS and GENERAL INFO: Use the provided Honey Knowledge and your own general knowledge.
        3. Keep answers concise and helpful.
        4. When appropriate, offer consumption advice from the 'Usage Instructions' section.
        
        Honey Knowledge & Policies:
        {$generalInfo}
        {$policies}

        Database Contents:
        {$productsContext}";

        try {
            // Groq is fast, so we don't strictly need 300s, but keeping it safe doesn't hurt.
            $response = Prism::text()
                ->using(Provider::Groq, 'llama-3.3-70b-versatile') // Switch to supported Groq model
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($question)
                ->withClientOptions(['timeout' => 60]) // Groq is fast, 60s is plenty
                ->usingTemperature(0.3) 
                ->withMaxTokens(500) // generous token limit for 70b
                ->generate();

            return $response->text;
        } catch (PrismRateLimitedException $e) {
            Log::warning("AI Rate Limited [{$e->getCode()}]: " . $e->getMessage());
            return "عذراً، المساعد مشغول حالياً بسبب كثرة الطلبات. يرجى المحاولة بعد قليل. ⏳";
        } catch (\Exception $e) {
            Log::error("AI Error: " . $e->getMessage());
            return "عذراً، حدث خطأ تقني: " . $e->getMessage();
        }
    }

    public function generateProductDescription(string $productName, ?string $categoryName = null): ?string
    {
        set_time_limit(180); // Increase max execution time for this request

        $systemPrompt = "You are a professional copywriter for a premium Saudi honey store 'مناهل وادي الجرفة' (Manahil Wadi Al-Jarfah).
        Write a short, attractive, and SEO-friendly product description in Arabic for the following honey product.
        
        Instructions:
        1. Reflect the premium and natural quality of the honey.
        2. You can use your general knowledge about the honey type to make the description richer.
        3. Keep it under 3-4 lines.
        4. OUTPUT MUST BE IN ARABIC ONLY. NO ENGLISH TRANSLATIONS.";

        $userType = $categoryName ? "Category: {$categoryName}\n" : "";
        $prompt = "{$userType}Product Name: {$productName}\nWrite a description:";

        try {
            $response = Prism::text()
                ->using(Provider::Groq, 'llama-3.3-70b-versatile')
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($prompt)
                ->withClientOptions(['timeout' => 60])
                ->usingTemperature(0.5) // Higher creativity for descriptions
                ->withMaxTokens(500)
                ->generate();

            return $response->text;
        } catch (PrismRateLimitedException $e) {
            Log::warning("AI Description Rate Limited [{$e->getCode()}]: " . $e->getMessage());
            return "المساعد مشغول حالياً، يرجى المحاولة لاحقاً.";
        } catch (\Exception $e) {
            Log::error("AI Description Error: " . $e->getMessage());
            return null;
        }
    }
}
