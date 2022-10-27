<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Cafe Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-15">
                        <div class="antialiased sans-serif">
                            <div v-if="user.type_id == 2" class="flex items-center justify-end">
                                <theme-button type="button" look="dark" slot="button" @pressed="create">
                                    <div class="pr-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                    New Cafe
                                </theme-button>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-1 pl-4">
                                    <div class="flex flex-row-reverse flex-wrap">
                                        <div v-for="(filter, filter_idx) in filters" :key="`filter_${filter_idx}`" class="relative pl-2 md:w-1/5">
                                            <div v-if="filter.type == 'search' || filter.type == 'text' || filter.type == 'email'">
                                                <input :type="filter.type"
                                                    class="w-full py-3 pl-10 pr-4 font-medium text-gray-600 rounded-lg shadow focus:outline-none focus:shadow-outline"
                                                    :placeholder="filter.placeholder" v-model="filter.value">
                                                <div v-if="filter.type == 'search'" class="absolute top-0 left-0 inline-flex items-center p-2 ml-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-400 w-7 h-7" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                                        <circle cx="10" cy="10" r="7" />
                                                        <line x1="21" y1="21" x2="15" y2="15" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-else-if="filter.type == 'select'" class="relative">
                                                <select 
                                                    class="block w-full px-4 py-3 pr-8 leading-tight text-pink-700 bg-pink-200 border border-pink-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-pink-500" 
                                                    v-model="filter.value"
                                                >
                                                    <option :value="null">{{ filter.placeholder }}</option>
                                                    <option v-for="opt in filter.options" :key="`opt_${opt.value}`" :value="opt.value">{{ opt.text }}</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center px-2 text-pink-700 pointer-events-none">
                                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="cafes.length > 0" class="flex flex-row flex-wrap items-start justify-start">
                                <div v-for="cafe in cafes" :key="`cafe_${cafe.id}`" class="w-1/4 p-4">
                                    <div class="flex flex-col items-center justify-center p-3 bg-pink-200 border-2 border-pink-700 rounded">
                                        <img :src="cafe.logo" alt="Logo" class="w-24 h-24 bg-white border-4 border-pink-700 rounded-full">
                                        <label class="py-2 text-xl">{{ cafe.name }}</label>
                                        <div v-if="getAvailableTable(cafe.tables) > 0" class="flex items-center justify-center w-full bg-green-200 border-2 border-green-600 rounded">
                                            <label class="w-full px-2 py-4 text-lg text-center text-green-700">{{ getAvailableTable(cafe.tables) }} tables available</label>
                                        </div>
                                        <div v-else class="flex items-center justify-center w-full bg-red-200 border-2 border-red-600 rounded">
                                            <label class="w-full px-2 py-4 text-lg text-center text-red-700">All tables are occupied</label>
                                        </div>
                                        <div class="flex items-center justify-end w-full pt-3 mt-3 border-t-2 border-pink-700">
                                            <theme-button type="button" look="secondary" @pressed="detail(cafe)">
                                                 <div class="pr-3">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                                </div>
                                                {{ user.type_id == 3 ? 'Book Table' : 'View Details' }}
                                            </theme-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="flex flex-col flex-wrap items-center justify-center">
                                <img class="h-64 bg-white" src="/images/no-cafe.svg" alt="No-Cafe">
                                <label class="pt-10 text-xl">No cafe available to show</label>
                            </div>

                            <edit-modal v-if="user.type_id == 2" :open="editOpen" :cafe_index_route="cafe_index_route" :cafe_prop="selectedCafe" @fetch-cafes="fetchCafes" @hide-modal="hideModal" @cancel-edit="cancelEdit" @update-cafe="updateCafe" />
                            <detail-modal v-if="user.type_id == 1 || user.type_id == 2" :open="detailOpen" :cafe_index_route="cafe_index_route" :cafe_prop="selectedCafe" :user="user" :cafe_table_statusses="cafe_table_statusses" @fetch-cafes="fetchCafes" @hide-modal="hideModal" @cancel-edit="cancelEdit" @edit-cafe="editCafe"/>

                            <book-table v-if="user.type_id == 3" :open="bookingOpen" :cafe="selectedCafe" :user="user" :booking_index_route="booking_index_route" :booking_dashboard_route="booking_dashboard_route" @cancel-edit="cancelEdit" />
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
    import ThemeButton from './../../Components/Button'
    import EditModal from './Edit'
    import DetailModal from './Detail'
    import BookTable from './BookTable'

    import _ from "lodash"

    export default {
        props: ['user', 'cafe_table_statusses', 'cafe_index_route', 'booking_index_route', 'booking_dashboard_route'],
        components: {
            AppLayout,
            DataTable,
            ThemeButton,
            EditModal,
            DetailModal,
            BookTable
        },
        data() {
            return {
                cafes: [],
                selectedCafe: null,
                editOpen: false,
                detailOpen: false,
                bookingOpen: false,
                selectedRows: [],
                filters: [
                    {
                        type: 'search',
                        label: 'Keyword',
                        placeholder: 'Search...',
                        name: 'keyword',
                        value: null
                    },
                    // {
                    //     type: 'select',
                    //     label: 'Status',
                    //     placeholder: 'Choose Booking Status...',
                    //     name: 'status_id',
                    //     options: this.booking_statusses.map(({ id, status }) => { 
                    //         return {
                    //             text: status, 
                    //             value: id
                    //         }
                    //     }),
                    //     value: null
                    // },
                ]
            }
        },
        mounted() {
            this.fetchCafes();
            let self = this;
            Echo.private(`tables`)
                .listen('TableStatusUpdated', (e) => {
                    self.updateTableStatus(e.cafe_table)
                });
        },
        watch: {
            params: _.debounce(function() {
                this.fetchCafes();
            }, 500)
        },
        computed: {
            params: function() {
                let obj= {};
                this.filters.forEach(filter => {
                    obj[filter.name] = filter.value;
                });
                return obj;
            }
        },
        methods: {
            fetchCafes: function() {
                let self = this;
                axios.get(this.cafe_index_route, {
                    params: this.params
                })
                    .then(response => {
                        self.cafes = response.data
                    });
            },
            create: function() {
                this.selectedCafe = {
                    name: null,
                    tables: []
                }
                this.editOpen = true
            },
            detail: function(cafe) {
                this.selectedCafe = cafe
                if (this.user.type_id == 1 || this.user.type_id == 2) {
                    this.detailOpen = true
                } else {
                    this.bookingOpen = true
                }
            },
            hideModal: function() {
                this.editOpen = false
            },
            cancelEdit: function() {
                this.selectedCafe = {
                    name: null,
                    tables: []
                }
                this.editOpen = false
                this.detailOpen = false
                this.bookingOpen = false
            },
            editCafe: function(cafe) {
                this.selectedCafe = cafe
                this.detailOpen = false
                this.editOpen = true
            },
            updateCafe: function(updatedCafe, is_new = false) {
                this.fetchCafes()
                // if (is_new) {
                //     this.cafes.push(updatedCafe)
                // } else {
                //     let idx = this.cafes.find(item => item.id == updatedCafe.id)
                //     if (idx >= 0) {
                //         this.$set(this.cafes, idx, updatedCafe)
                //     }
                // }
            },
            getAvailableTable: function(tables) {
                return tables.filter(tbl => tbl.status_id == 1).length
            },
            updateTableStatus: function(cafe_table) {
                let cafe_idx = this.cafes.findIndex(cafe => cafe.id == cafe_table.cafe_id)
                if (cafe_idx > -1) {
                    let tbl_idx = this.cafes[cafe_idx].tables.findIndex(tbl => tbl.id == cafe_table.id)
                    if (tbl_idx > -1) {
                        let status = this.cafe_table_statusses.find(stat => stat.id == cafe_table.status_id)
                        this.$set(this.cafes[cafe_idx].tables, tbl_idx, { ...this.cafes[cafe_idx].tables[tbl_idx], status, status_id: cafe_table.status_id })

                        if (this.selectedCafe.id == cafe_table.cafe_id) {
                            this.$set(this.selectedCafe.tables, tbl_idx, { ...this.selectedCafe.tables[tbl_idx], status, status_id: cafe_table.status_id })
                        }
                    }
                }
            }
        }
    }
</script>
