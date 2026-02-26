<x-layout title="إتمام الطلب">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" dir="rtl">
        <h1 class="text-3xl md:text-4xl font-black text-brand-navy mb-12 text-center">إتمام الطلب</h1>

        <div class="max-w-4xl mx-auto bg-brand-cream-light rounded-3xl shadow-2xl overflow-hidden border border-brand-gold/10 p-10 relative">
            <div class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            <div class="text-center mb-10">
                <div class="bg-emerald-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-100 shadow-sm">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-brand-navy">أهلاً بك، {{ Auth::user()->name }}</h2>
                <p class="text-gray-400 mt-2 font-medium">أنت الآن في صفحة إتمام الطلب الآمنة ومراجعة بياناتك.</p>
            </div>

            <div class="border-t border-brand-gold/10 pt-8">
                <h3 class="text-xl font-black text-brand-navy mb-6">ملخص الطلب</h3>
                <div class="flex justify-between items-center bg-brand-beige/50 p-6 rounded-2xl border border-brand-gold/5">
                    <span class="text-gray-500 font-bold text-lg">الإجمالي النهائي (شامل الضريبة):</span>
                    <span class="text-3xl font-black text-brand-gold flex items-center gap-2">
                        {{ number_format($total * 1.15) }}
                        <x-currency-symbol class="w-8 h-8" />
                    </span>
                </div>
            </div>

            <div class="mt-8 text-right">
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Shipping Address -->
                    <div class="bg-brand-cream/50 p-8 rounded-2xl border border-brand-gold/10">
                        <h3 class="text-xl font-black text-brand-navy mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            عنوان التوصيل
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="customer_phone" :value="__('رقم الجوال (للتواصل عند التوصيل)')" />
                                <x-text-input id="customer_phone" class="block mt-1 w-full" type="tel" name="customer_phone" placeholder="05xxxxxxxx" required />
                                <p class="text-[10px] text-gray-400 mt-1 mr-1">يرجى إدخال رقم الجوال لضمان وصول المندوب إليك.</p>
                            </div>
                            <div>
                                <x-input-label for="city" :value="__('المدينة')" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" required />
                            </div>
                            <div>
                                <x-input-label for="postal_code" :value="__('الرمز البريدي')" />
                                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" />
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="address" :value="__('العنوان التفصيلي (الحي، الشارع، المعلم)')" />
                                <textarea name="address" id="address" rows="3" class="block mt-1 w-full border-gray-200 focus:border-brand-gold focus:ring-brand-gold rounded-xl shadow-sm transition-all" required></textarea>
                            </div>
                            
                            <div class="col-span-1 md:col-span-2">
                                <label class="block font-black text-sm text-brand-navy mb-2 mr-1">حدد موقعك على الخريطة (اختياري)</label>
                                <div id="map" class="w-full rounded-2xl border-2 border-brand-gold/10 p-1 bg-white" style="height: 400px; z-index: 1;"></div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <div class="flex justify-between items-start mt-2">
                                    <p class="text-xs text-gray-500">اضغط على الخريطة لتحديد موقع التوصيل بدقة.</p>
                                    <a id="google-maps-link" href="#" target="_blank" class="hidden text-xs text-brand-gold hover:text-brand-gold-dark underline items-center gap-1 font-bold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        تأكيد الموقع في Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-brand-cream/50 p-8 rounded-2xl border border-brand-gold/10">
                        <h3 class="text-xl font-black text-brand-navy mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            طريقة الدفع
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex flex-col p-5 border-2 border-gray-100 rounded-[2rem] cursor-pointer hover:border-brand-gold/30 hover:bg-brand-cream/30 transition-all bg-white group select-none has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/5 has-[:checked]:shadow-lg has-[:checked]:shadow-brand-gold/10">
                                <input type="radio" name="payment_method" value="cod" class="absolute left-6 top-6 w-5 h-5 text-brand-gold border-gray-300 focus:ring-brand-gold" checked>
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-brand-gold/20 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <span class="font-black text-brand-navy text-lg block mb-1">الدفع عند الاستلام</span>
                                <span class="text-xs text-gray-400 font-medium">ادفع كاش عند وصول المنتج لبابك</span>
                            </label>
                            
                            <label class="relative flex flex-col p-5 border-2 border-gray-100 rounded-[2rem] cursor-pointer hover:border-brand-gold/30 hover:bg-brand-cream/30 transition-all bg-white group select-none has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/5 has-[:checked]:shadow-lg has-[:checked]:shadow-brand-gold/10">
                                <input type="radio" name="payment_method" value="card" class="absolute left-6 top-6 w-5 h-5 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-brand-gold/20 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <span class="font-black text-brand-navy text-lg block mb-1">بطاقة مدى / ائتمان</span>
                                <span class="text-xs text-gray-400 font-medium">دفع آمن وسريع عبر البطاقة</span>
                            </label>

                            <label class="relative flex flex-col p-5 border-2 border-gray-100 rounded-[2rem] cursor-pointer hover:border-brand-gold/30 hover:bg-brand-cream/30 transition-all bg-white group select-none has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/5 has-[:checked]:shadow-lg has-[:checked]:shadow-brand-gold/10">
                                <input type="radio" name="payment_method" value="tamara" class="absolute left-6 top-6 w-5 h-5 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-brand-gold/20 transition-colors">
                                    <img src="https://tamara.co/assets/logos/tamara-logo-short-en.svg" class="w-8 h-8 opacity-60">
                                </div>
                                <span class="font-black text-brand-navy text-lg block mb-1">تمارا (3 دفعات)</span>
                                <span class="text-xs text-gray-400 font-medium">قسم فاتورتك على 3 دفعات بدون فوائد</span>
                            </label>

                            <label class="relative flex flex-col p-5 border-2 border-gray-100 rounded-[2rem] cursor-pointer hover:border-brand-gold/30 hover:bg-brand-cream/30 transition-all bg-white group select-none has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/5 has-[:checked]:shadow-lg has-[:checked]:shadow-brand-gold/10">
                                <input type="radio" name="payment_method" value="tabby" class="absolute left-6 top-6 w-5 h-5 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-brand-gold/20 transition-colors">
                                    <img src="https://cdn.tabby.ai/assets/logo.svg" class="w-8 h-8 opacity-60">
                                </div>
                                <span class="font-black text-brand-navy text-lg block mb-1">تابي (4 دفعات)</span>
                                <span class="text-xs text-gray-400 font-medium">قسّمها على 4، بدون فوائد ولا رسوم</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-black py-4 px-8 rounded-xl shadow-xl transition-all w-full text-lg transform active:scale-[0.98]">
                        تأكيد الطلب والدفع
                    </button>
                </form>            
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Riyadh Coordinates as default
            var initialLat = 24.7136;
            var initialLng = 46.6753;
            var map = L.map('map').setView([initialLat, initialLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker;

            function updateLocation(lat, lng) {
                // Update Marker
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Update Hidden Inputs
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Update Google Maps Link
                var googleMapsLink = document.getElementById('google-maps-link');
                if (googleMapsLink) {
                    googleMapsLink.href = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
                    googleMapsLink.classList.remove('hidden');
                    googleMapsLink.classList.add('flex');
                }

                // Reverse Geocoding (Nominatim API)
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.address) {
                            // Populate City
                            var city = data.address.city || data.address.town || data.address.village || data.address.county || '';
                            if (city) document.getElementById('city').value = city;

                            // Populate Postal Code
                            var postcode = data.address.postcode || '';
                            if (postcode) document.getElementById('postal_code').value = postcode;

                            // Populate Detailed Address
                            var road = data.address.road || '';
                            var neighbourhood = data.address.neighbourhood || '';
                            var suburb = data.address.suburb || '';
                            var detailedAddress = [neighbourhood, suburb, road].filter(Boolean).join('، ');
                            
                            if (detailedAddress) {
                                document.getElementById('address').value = detailedAddress;
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching address:', error));
            }

            map.on('click', function(e) {
                updateLocation(e.latlng.lat, e.latlng.lng);
            });
            
            // Try to get user location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    updateLocation(lat, lng);
                });
            }
        });
    </script>
</x-layout>
