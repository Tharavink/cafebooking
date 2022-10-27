<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Booking Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-15">
                        <div class="antialiased sans-serif">
                            <data-table :headings_prop="headings" :rows_prop.sync="booking_data" :selectedRows_prop.sync="selectedRows" :bulkActions_prop.sync="bulkActions" :filters_prop.sync="filters" :actions="tableActions" @updateSelectedRows="updateSelectedRows" @rowUpdated="rowUpdated" @actionTriggered="actionTriggered" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import DataTable from './../../Components/DataTable'

    import _ from "lodash"

    export default {
        props: ['booking_index_route', 'booking_statusses'],
        components: {
            AppLayout,
            DataTable
        },
        data() {
            return {
                bookings: [],
                selectedRows: [],
                headings: [
                    {
                        'key': 'id',
                        'value': 'Booking ID',
                        'hidden': true
                    },
                    {
                        'key': 'user_id',
                        'value': 'User'
                    },
                    {
                        'key': 'cafe',
                        'value': 'Cafe'
                    },
                    {
                        'key': 'table_id',
                        'value': 'Table Number'
                    },
                    {
                        'key': 'sign_in',
                        'value': 'Sign In'
                    },
                    {
                        'key': 'sign_out',
                        'value': 'Sign Out'
                    },
                    {
                        'key': 'status',
                        'value': 'Status'
                    },
                ],
                tableActions: [
                    {
                        name: 'cancel',
                        display: 'Cancel',
                        look: 'error',
                        ignoreIf: {
                            name: 'status',
                            values: [3, 4, 5]
                        }
                    }
                ],
                bulkActions: [
                    {
                        type: 'select',
                        label: 'Change Booking Status',
                        placeholder: 'Choose Booking Status...',
                        name: 'status_id',
                        options: this.booking_statusses.map(({ id, status }) => { 
                            return {
                                text: status, 
                                value: id
                            }
                        }),
                        value: null
                    }
                ],
                filters: [
                    {
                        type: 'search',
                        label: 'Keyword',
                        placeholder: 'Search...',
                        name: 'keyword',
                        value: null
                    },
                    {
                        type: 'select',
                        label: 'Status',
                        placeholder: 'Choose Booking Status...',
                        name: 'status_id',
                        options: this.booking_statusses.map(({ id, status }) => { 
                            return {
                                text: status, 
                                value: id
                            }
                        }),
                        value: null
                    },
                ]
            }
        },
        mounted() {
            this.fetchBookings();
        },
        watch: {
            params: _.debounce(function() {
                this.fetchBookings();
            }, 500),
            bulkActions: {
                handler: function(val) {
                    this.applyAction(val);
                },
                deep: true
            }
        },
        computed: {
            booking_data: function() {
                let self = this;
                return this.bookings.map(booking => {
                    return {
                        id: {
                            value: booking.id,
                            display: booking.id
                        },
                        user_id: {
                            value: booking.user_id,
                            display: booking.user.name
                        },
                        cafe: {
                            value: booking.cafe_id,
                            display: booking.cafe.name
                        },
                        table_id: {
                            value: booking.table_id,
                            display: booking.cafe_table.number
                        },
                        sign_in: {
                            value: booking.sign_in,
                            display: booking.sign_in == null ? '-' : this.$moment(booking.sign_in).format('Do MMM YYYY | hh:mm'),
                            cssClass: 'font-bold'
                        },
                        sign_out: {
                            value: booking.sign_out,
                            display: booking.sign_out == null ? '-' : this.$moment(booking.sign_out).format('Do MMM YYYY | hh:mm'),
                            cssClass: 'font-bold'
                        },
                        status: {
                            value: booking.status_id,
                            display: booking.status.status,
                            cssClass: booking.status.cssClass
                        }
                    }
                });
            },
            params: function() {
                let obj= {};
                this.filters.forEach(filter => {
                    obj[filter.name] = filter.value;
                });
                return obj;
            }
        },
        methods: {
            fetchBookings: function() {
                let self = this;
                axios.get(this.booking_index_route, {
                    params: this.params
                })
                    .then(response => {
                        self.bookings = response.data
                    });
            },
            rowUpdated: function(val) {
                let booking = Object.fromEntries(Object.entries(val).map(([ key, obj ]) => [ key, obj.value ]));
                this.updateBooking(booking);
            },
            applyAction: function(actions) {
                let bookings = this.bookings.filter(booking => this.selectedRows.includes(booking.id));
                let self = this;
                bookings.forEach(booking => {
                    actions.forEach(action => {
                        if (action.value) {
                            booking[action.name] = action.value
                        }
                    });
                    self.updateBooking(booking);
                });
            },
            updateBooking: function(booking) {
                let self = this;
                axios.put(this.booking_index_route + '/' + booking.id, { booking })
                    .then(response => {
                        self.fetchBookings();
                    })
                    .catch(error => {
                        if (error.response.data.message) {
                            self.fetchBookings();
                        }
                    })
            },
            updateSelectedRows: function(selectedRows) {
                this.selectedRows = selectedRows;
            },
            actionTriggered: function(action, idx) {
                let self = this;
                let booking = this.bookings[idx];
                if (action == 'cancel') {
                    this.$swal.fire({
                        title: 'Are you sure?',
                        text: "Cancelling booking cannot be reverted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, cancel it!',
                        cancelButtonText: 'No, Go Back'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            booking.status_id = 5;
                            self.updateBooking(booking)
                        }
                    })
                    
                }
            }
        }
    }
</script>