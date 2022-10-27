<template>
    <sweet-modal blocking hide-close-button ref="modal" title="Book Table">
        <div v-if="cafe" class="flex flex-col items-center justify-center">
            <div class="flex flex-col items-center justify-center w-full py-2">
                <label class="pb-3 text-2xl">{{ cafe.name }}</label>
                <img :src="cafe.logo" class="object-cover w-24 h-24 mb-3 rounded-full" alt="">
                
                <div class="flex flex-row items-center justify-center w-full py-2 border-t-2 border-pink-700">
                    <label for="name" class="pb-3 pr-2 text-xl text-right">Number of person ({{ maximum }} Max)</label>
                    <div class="w-1/4">
                        <text-input type="number" :value="booking.number" min="1" :max="`${maximum}`" @input="updateValue('number', $event)" placeholder="Number of people..." />
                    </div>
                </div>
            </div>
        </div>
        <theme-button type="button" look="dark" slot="button" :tailwindStyle="'mr-2'" @pressed="$emit('cancel-edit')">
            <div class="pr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            Discard & Close
        </theme-button>
        <theme-button type="button" look="success" slot="button" @pressed="saveBooking()">
            <div class="pr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            Confirm Booking
        </theme-button>
    </sweet-modal>
</template>

<script>
import { SweetModal } from 'sweet-modal-vue'
import ThemeButton from '../../Components/Button'
import TextInput from '../../Components/TextInput'

export default {
    props: ['cafe', 'open', 'user', 'booking_index_route', 'booking_dashboard_route'],
    components: {
        SweetModal,
        ThemeButton,
        TextInput
    },
    data() {
        return {
            booking: {
                user_id: this.user.id,
                cafe_id: this.cafe ? this.cafe.id : '',
                number: '1'
            }
        }
    },
    watch: {
        open: function(val) {
            if (val) {
                this.$refs.modal.open()
            } else {
                this.$refs.modal.close()
            }
        },
        cafe: function(val) {
            this.booking.cafe_id = val.id
        }
    },
    computed: {
        maximum: function() {
            if (this.cafe && this.cafe.tables.length > 0) {
                return this.cafe.tables.reduce((max, tbl) => tbl.slots.filter(slot => slot.disallowed == 0).length > max ? tbl.slots.filter(slot => slot.disallowed == 0).length : max, this.cafe.tables[0].slots.filter(slot => slot.disallowed == 0).length)
            } else {
                return null
            }
        }
    },
    methods: {
        updateValue: function(field, val) {
            this.$set(this.booking, field, val)
        },
        saveBooking: function() {
            let self = this;
            axios.post(this.booking_index_route, this.booking)
                .then(response => {
                    this.$refs.modal.close()
                    
                    if (response.data.info) {
                        self.$swal.fire({
                            icon: 'info',
                            title: 'Tables Full',
                            text: response.data.info,
                            showCancelButton: true,
                            confirmButtonText: `Yes, Notify Me`,
                            cancelButtonText: `No, Thanks`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post(self.booking_index_route, { ...self.booking, is_notify: 1 })
                                    .then(response => {
                                        self.$emit('cancel-edit')
                                        self.$swal.fire('Got It!', 'We will notify you once a table is available', 'success')
                                    })
                            }
                            else {
                                self.$emit('cancel-edit')
                            }
                        })
                    } else {
                        self.$emit('cancel-edit')
                        self.$swal.fire({
                            icon: 'info',
                            title: 'Booking Confirmed',
                            text: `Table ${response.data.cafe_table.number} have been booked for you in ${response.data.cafe.name}. Please head to the cafe within 15 minutes, otherwise booking would be expired..`,
                            footer: `<a href="${self.booking_dashboard_route}" target="_blank"><u>Bring me to my booking dashboard</u></a>`
                        })
                    }
                })
        }
    }
}
</script>