<div x-data="chatWidget" class="z-[9999] font-cairo" style="direction: rtl;" x-cloak>

    <!-- Chat Window -->
    <div x-show="open && !minimized" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="fixed bottom-24 right-4 md:right-6 bg-white w-[90vw] md:w-96 rounded-2xl shadow-2xl flex flex-col border border-brand-gold/20 z-[9999] overflow-hidden"
         style="height: 550px; max-height: calc(100vh - 180px);">
        
        <!-- Header -->
        <div class="bg-brand-navy border-b border-brand-gold/20 p-4 flex justify-between items-center shadow-lg relative shrink-0">
            <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-gold/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-brand-gold/30">
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
            <div class="flex items-center gap-2 z-10">
                <!-- Close Button -->
                <button @click="open = false" title="إغلاق المحادثة"
                    class="text-white bg-red-500 hover:bg-red-600 transition p-1.5 rounded-lg flex items-center justify-center shadow-md border border-red-400/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div x-ref="scrollContainer" id="chat-scroll-container" class="flex-1 overflow-y-auto min-h-0 bg-gray-50/50 p-4 space-y-4 scroll-smooth">
            <template x-for="msg in messages" :key="msg.id">
                <div :class="{ 'flex justify-start': msg.sender === 'bot', 'flex justify-end': msg.sender === 'user' }">
                    <div :class="{
                        'bg-white text-brand-navy rounded-tl-none border border-brand-gold/30 shadow-sm': msg.sender === 'bot',
                        'bg-brand-navy text-white rounded-tr-none border border-brand-gold/20 shadow-md': msg.sender === 'user'
                    }"
                        class="max-w-[85%] rounded-2xl px-4 py-3 relative group text-sm leading-relaxed">
                        <p class="whitespace-pre-wrap break-words" x-text="msg.text"></p>
                        <span
                            class="text-[10px] opacity-0 group-hover:opacity-70 transition-opacity absolute bottom-0.5"
                            :class="{
                                'left-2 text-gray-400': msg.sender === 'bot',
                                'right-2 text-gray-300': msg.sender === 'user'
                            }"
                            x-text="msg.time"></span>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-white border border-brand-gold/30 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-brand-gold rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-1.5 h-1.5 bg-brand-gold rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-1.5 h-1.5 bg-brand-gold rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-200 shrink-0">
            <form @submit.prevent="sendMessage" class="flex gap-2 items-center">
                <input x-model="message" type="text" placeholder="اسألني هنا..."
                    class="flex-1 h-12 bg-gray-50 border border-gray-200 text-brand-navy rounded-xl px-4 focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all placeholder-gray-400 text-sm">

                <button type="submit" :disabled="!message.trim()"
                    class="h-12 w-12 bg-brand-navy hover:bg-brand-navy-light text-brand-gold disabled:opacity-50 disabled:cursor-not-allowed rounded-xl transition-all duration-300 transform active:scale-95 shadow-md flex items-center justify-center group/btn border border-brand-gold/20">
                    <svg class="w-6 h-6 transform -rotate-90 group-hover/btn:-translate-y-0.5 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button (Floating) -->
    <button @click="toggle()"
        :class="{ 'scale-0 opacity-0': open && !minimized, 'scale-100 opacity-100': !open || minimized }"
        class="fixed bottom-4 right-4 md:bottom-6 md:right-6 bg-brand-navy text-brand-gold w-16 h-16 rounded-full shadow-2xl hover:shadow-brand-gold/40 transition-all duration-500 flex items-center justify-center transform group border-2 border-brand-gold z-50">
        <span class="absolute inline-flex h-full w-full rounded-full bg-brand-gold opacity-20 animate-ping group-hover:hidden"></span>
        <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>

        <!-- Notification Badge if closed -->
        <span x-show="!open" class="absolute top-0 right-0 block h-4 w-4 rounded-full ring-2 ring-white bg-red-500 transform translate-x-0 -translate-y-0 shadow-lg border border-white"></span>
    </button>
</div>

<style>
    [x-cloak] { display: none !important; }

    /* رول (شريط التمرير) مخصص باللون الذهبي الواضح جداً داخل الرسائل */
    #chat-scroll-container::-webkit-scrollbar {
        width: 8px !important;
        display: block !important;
    }
    #chat-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 10px;
    }
    #chat-scroll-container::-webkit-scrollbar-thumb {
        background-color: #D4AF37 !important; /* لون ذهبي */
        border-radius: 10px;
        border: 2px solid #f1f1f1;
    }
    #chat-scroll-container::-webkit-scrollbar-thumb:hover {
        background-color: #b5952f !important;
    }
    
    /* فايرفوكس */
    #chat-scroll-container {
        scrollbar-width: thin;
        scrollbar-color: #D4AF37 #f1f1f1;
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
                            text: 'مرحباً! أنا خبير العسل في متجر رِواء. كيف يمكنني مساعدتك اليوم؟ 🍯',
                            sender: 'bot',
                            time: new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })
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
                    time: new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })
                });
                this.message = '';
                this.isTyping = true;
                this.$nextTick(() => this.scrollToBottom());
                fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userMsg })
                }).then(res => res.json()).then(data => {
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now(),
                        text: data.reply,
                        sender: 'bot',
                        time: new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })
                    });
                    this.$nextTick(() => this.scrollToBottom());
                }).catch(error => {
                    console.error('Error:', error);
                    this.isTyping = false;
                    this.messages.push({
                        id: Date.now(),
                        text: 'عذراً، حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.',
                        sender: 'bot',
                        time: new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })
                    });
                });
            },
            scrollToBottom() {
                const el = document.getElementById('chat-scroll-container');
                if (el) el.scrollTop = el.scrollHeight;
            }
        }));
    });
</script>
