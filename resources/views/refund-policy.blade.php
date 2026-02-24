<x-layout title="سياسة الاستبدال والاسترجاع | مناحل وادي الجرفة">
    <div class="bg-amber-50 py-12">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-amber-100">
                <div class="bg-amber-500 py-8 px-10 text-center relative overflow-hidden">
                    <h1 class="text-3xl md:text-4xl font-black text-white relative z-10">سياسة الاستبدال والاسترجاع</h1>
                    <p class="text-amber-100 mt-2 relative z-10">رضاكم هو غايتنا وجودة منتجاتنا هي فخرنا</p>
                </div>

                <div class="p-8 md:p-12 space-y-10 leading-relaxed text-gray-700 text-right" dir="rtl">
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 border-r-4 border-amber-500 pr-4 mb-4">طبيعة المنتج</h2>
                        <p>نظراً لأن منتجاتنا هي منتجات غذائية طبيعية (عسل نحل ومشتقاته)، وحرصاً على الصحة العامة وسلامة عملائنا، فإننا نتبع معايير صارمة في الاستبدال والاسترجاع.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 border-r-4 border-amber-500 pr-4 mb-4">حالات الاسترجاع المسموحة</h2>
                        <div class="bg-red-50 p-5 rounded-xl border border-red-100">
                             <p class="font-bold text-red-800 mb-2 underline">يتم استبدال أو استرجاع المنتج في الحالات التالية فقط:</p>
                             <ul class="list-disc list-inside space-y-2 pr-2 text-red-900">
                                <li>وصول المنتج تالفاً أو مكسوراً نتيجة عملية الشحن.</li>
                                <li>وجود خطأ في نوع المنتج المسلم (استلام منتج مختلف عن المطلوب).</li>
                                <li>وجود عيب واضح في جودة المنتج أو اختلافه عن المواصفات المذكورة.</li>
                             </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 border-r-4 border-amber-500 pr-4 mb-4">شروط وفترة الإبلاغ</h2>
                        <ul class="list-disc list-inside space-y-3 pr-2">
                            <li>يجب الإبلاغ عن أي مشكلة خلال <strong>3 أيام عمل</strong> كحد أقصى من تاريخ استلام الطلب.</li>
                            <li>يجب أن يكون المنتج بحالته الأصلية ولم يتم فتحه أو استخدامه (إلا في حال وجود عيب في الجودة).</li>
                            <li>يُشترط توفير صورة واضحة توضح التلف أو الخطأ في الطلب.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 border-r-4 border-amber-500 pr-4 mb-4">إجراءات الاسترجاع</h2>
                        <p>عند ثبوت أحقية العميل في الاسترجاع أو الاستبدال:</p>
                        <ul class="list-decimal list-inside space-y-2 pr-2 mt-3 font-semibold">
                            <li>يتم التواصل معنا عبر وسائل التواصل المعتمدة.</li>
                            <li>نتحمل نحن كافة تكاليف الشحن في حال كان الخطأ من طرفنا أو كان المنتج تالفاً.</li>
                            <li>يتم تحويل المبلغ المسترد إلى حساب العميل خلال 7-14 يوم عمل بعد وصول المنتج إلينا وفحصه.</li>
                        </ul>
                    </section>
                </div>
                
                <div class="bg-gray-50 p-6 text-center border-t border-gray-100">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-full transition shadow-lg">
                        <span>العودة للرئيسية</span>
                        <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
