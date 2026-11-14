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

        $systemPrompt = "أنت خبير عسل سعودي محترف تعمل في 'متجر رِواء'.
وظيفتك مساعدة العملاء في اختيار العسل، شرح فوائده، والإجابة عن الأسعار بأسلوب سعودي ودود ومرحب.

الشخصية والأسلوب:
- استخدم عبارات ترحيبية سعودية مثل 'يا هلا'، 'أبشر'، 'حياك الله'، 'طال عمرك'.
- كن دافئاً، خبيراً، وجديراً بالثقة.
- **مهم جداً: الإجابة يجب أن تكون باللغة العربية الفصحى المطّعمة باللهجة السعودية البيضاء. ممنوع منعاً باتاً كتابة أي كلمة إنجليزية أو أرقام إنجليزية.**

منطق التوصيات:
- إذا سأل العميل عن 'القولون' أو المعدة -> انصحه فوراً بعسل الطلح.
- إذا سأل عن عسل 'نادر' أو 'فاخر' -> انصحه بعسل المجرى واذكر قوامه المتبلور وندرته.
- إذا سأل عن 'المناعة' -> انصحه بعسل السدر.

التعليمات:
1. للأسعار والمنتجات: استخدم فقط قاعدة البيانات المرفقة.
2. للفوائد والمعلومات: اعتمد على المعلومات المرفقة أدناه.
3. اجعل إجاباتك قصيرة، مفيدة، ومباشرة.
4. قدم نصائح حول طريقة الاستخدام عند الحاجة.

معلومات حول العسل والسياسات:
{$generalInfo}
{$policies}

محتويات قائمة المنتجات المتوفرة:
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

        $systemPrompt = "أنت كاتب محتوى إعلاني (Copywriter) محترف تعمل لصالح متجر عسل سعودي فاخر باسم 'متجر رِواء'.
مهمتك كتابة وصف منتج قصير، جذاب، ومتوافق مع محركات البحث المتضمنة للكلمات المفتاحية الطبيعية، وذلك لمنتج العسل التالي.

التعليمات الصارمة:
1. الجودة: ابرز وعزز فكرة الجودة العالية والطبيعية الفاخرة للعسل.
2. الفوائد: استخدم خبرتك لإبراز فوائد ومميزات هذا النوع تحديداً من العسل.
3. التنسيق: الوصف يجب أن يكون فقرة قصيرة ومؤثرة (حوالي 3 إلى 4 أسطر).
4. **مهم جداً وحاسم: المخرج النهائي يجب أن يكون باللغة العربية فقط. يُمنع منعاً باتاً كتابة أي حرف، كلمة، أو مصطلح باللغة الإنجليزية.**";

        $userType = $categoryName ? "تصنيف المنتج: {$categoryName}\n" : "";
        $prompt = "{$userType}اسم المنتج: {$productName}\nاكتب وصفاً جذاباً واحترافياً لهذا المنتج:";

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
