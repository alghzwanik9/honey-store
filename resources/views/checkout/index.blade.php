<x-layout title="إتمام الطلب">
    <div class="container mx-auto px-4 py-12" dir="rtl">
        <h1 class="text-3xl md:text-4xl font-black text-brand-navy mb-12 text-center">إتمام الطلب</h1>

        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-brand-gold/10 p-8">
            <div class="text-center mb-8">
                <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2 class="text-2xl font-bold text-gray-800">أهلاً بك، {{ Auth::user()->name }}</h2>
                <p class="text-gray-600 mt-2">أنت الآن في صفحة إتمام الطلب الآمنة.</p>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">ملخص الطلب</h3>
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-600">الإجمالي النهائي:</span>
                    <span class="text-xl font-black text-brand-gold flex items-center gap-3" style="gap: 1rem;">
                        {{ number_format($total * 1.15) }}
                        <svg class="w-6 h-6 text-brand-gold drop-shadow-sm mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="mt-8 text-right">
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Shipping Address -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-black text-brand-navy mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            عنوان التوصيل
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <label class="block font-medium text-sm text-gray-700 mb-2">حدد موقعك على الخريطة (اختياري)</label>
                                <div id="map" class="w-full rounded-lg border border-gray-300" style="height: 400px; z-index: 1;"></div>
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
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-black text-brand-navy mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            طريقة الدفع
                        </h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-white hover:border-brand-gold transition-all bg-white group">
                                <input type="radio" name="payment_method" value="cod" class="w-4 h-4 text-brand-gold border-gray-300 focus:ring-brand-gold" checked>
                                <span class="mr-3 font-bold text-brand-navy group-hover:text-brand-gold transition-colors">الدفع عند الاستلام (COD)</span>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-white hover:border-brand-gold transition-all bg-white group">
                                <input type="radio" name="payment_method" value="card" class="w-4 h-4 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <span class="mr-3 font-bold text-brand-navy group-hover:text-brand-gold transition-colors">بطاقة مدى / ائتمانية</span>
                            </label>

                            <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-white hover:border-brand-gold transition-all bg-white group">
                                <input type="radio" name="payment_method" value="tamara" class="w-4 h-4 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <div class="mr-3">
                                    <span class="block font-bold text-brand-navy group-hover:text-brand-gold transition-colors">تمارا (Tamara)</span>
                                    <span class="block text-xs text-gray-400">قسم فاتورتك على 3 دفعات بدون فوائد</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-white hover:border-brand-gold transition-all bg-white group">
                                <input type="radio" name="payment_method" value="tabby" class="w-4 h-4 text-brand-gold border-gray-300 focus:ring-brand-gold">
                                <div class="mr-3">
                                    <span class="block font-bold text-brand-navy group-hover:text-brand-gold transition-colors">تابي (Tabby)</span>
                                    <span class="block text-xs text-gray-400">قسم فاتورتك على 4 دفعات بدون فوائد</span>
                                </div>
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
