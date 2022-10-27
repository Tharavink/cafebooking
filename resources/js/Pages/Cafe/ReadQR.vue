<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Check IN / Check Out
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-15">
                        <div v-if="errorText" class="flex flex-col items-center justify-center w-full">
                            <div class="text-red-700">
                                <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <label class="text-4xl">{{ errorText }}</label>
                        </div>
                        <div v-else-if="bookings && bookings.length > 0" class="w-full">
                            <div v-for="(booking, idx) in bookings" :key="`booking_${booking.id}`" class="flex flex-col items-center justify-center w-full py-10 border-b border-gray-500">
                                <img :src="booking.cafe.logo" class="object-cover w-24 h-24 mb-3 rounded-full" alt="">
                                <label class="pb-3 text-2xl">{{ booking.cafe.name }}</label>
                                <label class="pb-3 text-2xl">Table Number</label>
                                <label class="pb-3 text-4xl font-bold text-green-700">{{ booking.cafe_table.number }}</label>

                                <div class="flex flex-row items-center justify-center w-full">
                                    <label v-if="booking.sign_in != null" class="py-5 pr-2 text-xl font-bold text-green-500">Checked In at {{ booking.sign_in | moment("h:mm A") }}</label>
                                    <label v-if="booking.sign_in != null && booking.sign_out != null" class="py-5 text-xl font-bold text-gray-500">|</label>
                                    <label v-if="booking.sign_out != null" class="py-5 pl-2 text-xl font-bold text-red-500">Checked Out at {{ booking.sign_out | moment("h:mm A") }}</label>
                                </div>

                                <div class="flex flex-row items-center justify-center w-full">
                                    <theme-button type="button" look="secondary" tailwindStyle="mr-2" :disabled="booking.sign_in != null" @pressed="updateBooking(idx, 1, 0)">
                                        <div class="pr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        Check In
                                    </theme-button>
                                    <theme-button type="button" look="error" :disabled="booking.sign_in == null || (booking.sign_in != null && booking.sign_out != null)" @pressed="updateBooking(idx, 0, 1)">
                                        <div class="pr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        </div>
                                        Check Out
                                    </theme-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
    
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import ThemeButton from './../../Components/Button'
    
    export default {
        props: ['cafe_id', 'booking_index_route'],
        components: {
            AppLayout,
            ThemeButton
        },
        data() {
            return {
                errorText: null,
                bookings: []
            }
        },
        mounted() {
            this.fetchBookings();
        },
        methods: {
            fetchBookings: function() {
                let self = this
                axios.get(`${this.booking_index_route}/${this.cafe_id}`)
                    .then(response => {
                        if (response.data.error) {
                            self.errorText = response.data.error
                        } else {
                            self.bookings = response.data
                        }
                    })
            },
            updateBooking: function(idx, is_sign_in, is_sign_out) {
                let booking = this.bookings[idx]
                let self = this
                axios.put(`${this.booking_index_route}/${booking.id}`, { booking, is_sign_in, is_sign_out })
                    .then(response => {
                        if (response.data.error) {
                            self.$swal('Error', response.data.error, 'error')
                        } else {
                            self.$set(self.bookings, idx, response.data)
                            if (is_sign_in) {
                                self.$swal('Checked-In Successfully', 'Enjoy your meal at the cafe', 'success')
                            } else {
                                self.$swal('Checked-Out Successfully', 'Thank you for using our app. Hope to see you soon.', 'success')
                            }
                        }
                    })
            }
        }
    }
</script>