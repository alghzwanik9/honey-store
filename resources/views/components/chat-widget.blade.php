<div x-data="chatWidget" class="z-50 font-cairo" style="direction: rtl; position: fixed; bottom: 20px; right: 20px;"
    x-cloak>

    <div x-show="open && !minimized" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-brand-gold/20 mb-4"
         style="height: 500px; width: 360px; max-width: 95vw; max-height: 85vh; display: flex; flex-direction: column; position: relative;">

        <div class="bg-brand-navy border-b border-brand-gold/20 p-4 flex justify-between items-center shadow-lg relative overflow-hidden"
            style="height: 75px; flex-shrink: 0;">
            <div
                class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold">
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-brand-gold/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-brand-gold/30">
                    <span class="text-xl">🐝</span>
                </div>
                <div>
                    <h3 class="font-bold text-brand-gold text-lg leading-tight">المساعد الذكي</h3>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-gray-300 text-xs">متاح الآن</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="minimized = true"
                    class="text-gray-200 hover:text-white transition p-1.5 hover:bg-white/10 rounded-full bg-white/5" title="تصغير">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                    </svg>
                </button>
                <button @click="open = false"
                    class="text-white bg-red-500/40 hover:bg-red-500 transition p-1.5 rounded-lg border border-red-500/20" title="إغلاق">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-ref="scrollContainer" id="chat-scroll-container" class="flex-1 bg-gray-50/50 p-4 space-y-4 scroll-smooth"
            style="overflow-y: auto !important; display: flex; flex-direction: column; min-height: 0; flex: 1;">

            <template x-for="msg in messages" :key="msg.id">
                <div
                    :class="{ 'flex justify-start': msg.sender === 'bot', 'flex justify-end': msg.sender === 'user' }">
                    <div :class="{
                        'bg-white text-brand-navy rounded-tl-none border border-brand-gold/10': msg
                            .sender === 'bot',
                        'bg-brand-navy text-white rounded-tr-none border border-brand-gold/20': msg
                            .sender === 'user'
                    }"
                        class="max-w-[85%] rounded-2xl px-4 py-3 shadow-sm relative group text-sm leading-relaxed">
                        <p class="whitespace-pre-wrap break-words" x-text="msg.text"></p>
                        <span
                            class="text-[10px] opacity-0 group-hover:opacity-70 transition-opacity absolute bottom-0.5"
                            :class="{ 'left-2 text-gray-400': msg.sender === 'bot', 'right-2 text-gray-300': msg
                                    .sender === 'user' }"
                            x-text="msg.time"></span>
                    </div>
                </div>
            </template>

            <div x-show="isTyping" class="flex justify-start">
                <div
                    class="bg-white border border-gray-100 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                        style="animation-delay: 0ms"></span>
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce"
                        style="animation-delay: 150ms"></span>
                    <span class="w-1.5 h-1.5 bg-gray-600 rounded-full animate-bounce"
                        style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border-t border-gray-100" style="height: 85px; flex-shrink: 0;">
            <form @submit.prevent="sendMessage" class="flex gap-2 items-center">
                <input x-model="message" type="text" placeholder="اسألني هنا..."
                    class="flex-1 h-12 bg-gray-50 border-brand-gold/10 text-brand-navy rounded-xl px-4 focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold transition-all text-sm">

                <button type="submit" :disabled="!message.trim()"
                    class="h-12 w-12 bg-brand-navy hover:bg-brand-gold text-brand-gold hover:text-brand-navy disabled:opacity-50 rounded-xl transition-all flex items-center justify-center">
                    <svg class="w-6 h-6 transform -rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            <div class="text-center mt-1">
                <span class="text-[9px] text-gray-400">مدعوم بالذكاء الاصطناعي</span>
            </div>
        </div>
    </div>

    <button @click="toggle()"
        :class="{ 'scale-0 opacity-0': open && !minimized, 'scale-100 opacity-100': !open || minimized }"
        class="bg-brand-navy hover:bg-brand-navy-light text-brand-gold w-16 h-16 rounded-full shadow-2xl transition-all duration-500 flex items-center justify-center transform border-2 border-brand-gold/40">
        <span class="absolute inline-flex h-full w-full rounded-full bg-brand-gold opacity-20 animate-ping"></span>
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
        </svg>
    </button>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    /* تنسيق شريط التمرير (المؤشر) ليكون واضحاً وقابلاً للسحب بالفارة */
    #chat-scroll-container::-webkit-scrollbar {
        width: 8px !important;
        height: 8px !important;
        display: block !important;
    }

    #chat-scroll-container::-webkit-scrollbar-track {
        background: #f8f9fa !important;
        border-radius: 10px;
    }

    #chat-scroll-container::-webkit-scrollbar-thumb {
        background: #D4AF37 !important;
        /* ذهبي وادي الجربة */
        border-radius: 10px;
        border: 2px solid #f8f9fa;
    }

    #chat-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #B8860B !important;
    }

    /* لمتصفح فايرفوكس */
    #chat-scroll-container {
        scrollbar-width: thin;
        scrollbar-color: #D4AF37 #f8f9fa;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatWidget', () => ({
            open: false,
            minimized: false,
            message: '',
            messages: [],
            isTyping: false,

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.minimized = false;
                    this.$nextTick(() => this.scrollToBottom());
                    if (this.messages.length === 0) {
                        this.messages.push({
                            id: Date.now(),
                            text: 'مرحباً! أنا خبير العسل في مناحل وادي الجرفة. كيف يمكنني مساعدتك اليوم؟ 🍯',
                            sender: 'bot',
                            time: new Date().toLocaleTimeString('ar-SA', {
                                hour: '2-digit',
                                minute: '2-digit'
                            })
                        });
                    }
                }
            },

            sendMessage() {
                if (!this.message.trim()) return;
                const userMsg = this.message;
                this.messages.push({
                    id: Date.now(),
                    text: userMsg,
                    sender: 'user',
                    time: new Date().toLocaleTimeString('ar-SA', {
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                });
                this.message = '';
                this.isTyping = true;
                this.$nextTick(() => this.scrollToBottom());

                fetch('{{ route('chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')
                                .content
                        },
                        body: JSON.stringify({
                            message: userMsg
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isTyping = false;
                        this.messages.push({
                            id: Date.now(),
                            text: data.reply,
                            sender: 'bot',
                            time: new Date().toLocaleTimeString('ar-SA', {
                                hour: '2-digit',
                                minute: '2-digit'
                            })
                        });
                        this.$nextTick(() => this.scrollToBottom());
                    })
                    .catch(() => {
                        this.isTyping = false;
                        this.messages.push({
                            id: Date.now(),
                            text: 'عذراً، حدث خطأ. حاول ثانية.',
                            sender: 'bot',
                            time: 'الآن'
                        });
                    });
            },

            scrollToBottom() {
                const container = this.$refs.scrollContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }));
    });
</script>
