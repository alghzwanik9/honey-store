<x-layout title="سياسة الاستبدال والاسترجاع | مناحل وادي الجرفة">
    <div class="bg-brand-cream py-16">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto bg-brand-cream-light rounded-[3rem] shadow-2xl overflow-hidden border border-brand-gold/10 relative">
                <div class="absolute top-0 right-0 w-full h-2 bg-brand-gold"></div>
                <div class="bg-brand-navy py-12 px-10 text-center relative overflow-hidden">
                    <h1 class="text-4xl md:text-5xl font-black text-brand-gold relative z-10 px-4">سياسة الاستبدال والاسترجاع</h1>
                    <p class="text-gray-400 mt-4 relative z-10 text-lg">رضاكم هو غايتنا وجودة منتجاتنا هي فخرنا بكل فخر</p>
                </div>

                <div class="p-8 md:p-12 space-y-10 leading-relaxed text-gray-700 text-right" dir="rtl">
                    <section>
                        <h2 class="text-2xl font-black text-brand-navy border-r-4 border-brand-gold pr-4 mb-5">طبيعة المنتج</h2>
                        <p>نظراً لأن منتجاتنا هي منتجات غذائية طبيعية (عسل نحل ومشتقاته)، وحرصاً على الصحة العامة وسلامة عملائنا، فإننا نتبع معايير صارمة في الاستبدال والاسترجاع.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-brand-navy border-r-4 border-brand-gold pr-4 mb-5">حالات الاسترجاع المسموحة</h2>
                        <div class="bg-red-50/50 p-8 rounded-[2rem] border border-red-100/50 shadow-inner">
                             <p class="font-black text-red-800 mb-4 flex items-center gap-2">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                 يتم استبدال أو استرجاع المنتج في الحالات التالية فقط:
                             </p>
                             <ul class="list-disc list-inside space-y-3 pr-2 text-red-900 font-bold leading-relaxed">
                                <li>وصول المنتج تالفاً أو مكسوراً نتيجة عملية الشحن.</li>
                                <li>وجود خطأ في نوع المنتج المسلم (استلام منتج مختلف عن المطلوب).</li>
                                <li>وجود عيب واضح في جودة المنتج أو اختلافه عن المواصفات المذكورة.</li>
                             </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-brand-navy border-r-4 border-brand-gold pr-4 mb-5">شروط وفترة الإبلاغ</h2>
                        <ul class="list-disc list-inside space-y-3 pr-2">
                            <li>يجب الإبلاغ عن أي مشكلة خلال <strong>3 أيام عمل</strong> كحد أقصى من تاريخ استلام الطلب.</li>
                            <li>يجب أن يكون المنتج بحالته الأصلية ولم يتم فتحه أو استخدامه (إلا في حال وجود عيب في الجودة).</li>
                            <li>يُشترط توفير صورة واضحة توضح التلف أو الخطأ في الطلب.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-brand-navy border-r-4 border-brand-gold pr-4 mb-5">إجراءات الاسترجاع</h2>
                        <p>عند ثبوت أحقية العميل في الاسترجاع أو الاستبدال:</p>
                        <ul class="list-decimal list-inside space-y-2 pr-2 mt-3 font-semibold">
                            <li>يتم التواصل معنا عبر وسائل التواصل المعتمدة.</li>
                            <li>نتحمل نحن كافة تكاليف الشحن في حال كان الخطأ من طرفنا أو كان المنتج تالفاً.</li>
                            <li>يتم تحويل المبلغ المسترد إلى حساب العميل خلال 7-14 يوم عمل بعد وصول المنتج إلينا وفحصه.</li>
                        </ul>
                    </section>
                </div>
                
                <div class="bg-brand-beige/10 p-10 text-center border-t border-brand-gold/5">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-brand-navy hover:bg-brand-gold text-brand-gold hover:text-brand-navy font-black py-4 px-10 rounded-xl transition-all duration-300 shadow-xl group">
                        <span>العودة للرئيسية</span>
                        <svg class="w-5 h-5 rotate-180 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
