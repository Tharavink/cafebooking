<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-15">
                        <div class="antialiased sans-serif">
                            <data-table :headings_prop="headings" :rows_prop.sync="user_data" :selectedRows_prop.sync="selectedRows" :bulkActions_prop.sync="bulkActions" :filters_prop.sync="filters" @updateSelectedRows="updateSelectedRows" @rowUpdated="rowUpdated" />
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
        props: ['user_index_route', 'user_types'],
        components: {
            AppLayout,
            DataTable
        },
        data() {
            return {
                users: [],
                open: false,
                selectedRows: [],
                headings: [
                    {
                        'key': 'id',
                        'value': 'User ID',
                        'hidden': true
                    },
                    {
                        'key': 'name',
                        'value': 'Name'
                    },
                    {
                        'key': 'email',
                        'value': 'Email'
                    },
                    {
                        'key': 'type_id',
                        'value': 'User Type'
                    }
                ],
                bulkActions: [
                    {
                        type: 'select',
                        label: 'Change User Type',
                        placeholder: 'Choose User Type...',
                        name: 'type_id',
                        options: this.user_types.map(({ id, name }) => { 
                            return {
                                text: name, 
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
                        label: 'User Type',
                        placeholder: 'Choose User Type...',
                        name: 'type_id',
                        options: this.user_types.map(({ id, name }) => { 
                            return {
                                text: name, 
                                value: id
                            }
                        }),
                        value: null
                    },
                ]
            }
        },
        mounted() {
            this.fetchUsers();
        },
        watch: {
            params: _.debounce(function() {
                this.fetchUsers();
            }, 500),
            bulkActions: {
                handler: function(val) {
                    this.applyAction(val);
                },
                deep: true
            }
        },
        computed: {
            user_data: function() {
                let self = this;
                return this.users.map(user => {
                    return {
                        id: {
                            value: user.id,
                            display: user.id
                        },
                        name: {
                            value: user.name,
                            display: user.name
                        },
                        email: {
                            value: user.email,
                            display: user.email
                        },
                        type_id: {
                            value: user.type_id,
                            display: user.type.name
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
            fetchUsers: function() {
                let self = this;
                axios.get(this.user_index_route, {
                    params: this.params
                })
                    .then(response => {
                        self.users = response.data
                    });
            },
            rowUpdated: function(val) {
                let user = Object.fromEntries(Object.entries(val).map(([ key, obj ]) => [ key, obj.value ]));
                this.updateUser(user);
            },
            applyAction: function(actions) {
                let users = this.users.filter(user => this.selectedRows.includes(user.id));
                let self = this;
                users.forEach(user => {
                    actions.forEach(action => {
                        if (action.value) {
                            user[action.name] = action.value
                        }
                    });
                    self.updateUser(user);
                });
            },
            updateUser: function(user) {
                let self = this;
                axios.put(this.user_index_route + '/' + user.id, { user })
                    .then(response => {
                        self.fetchUsers();
                    })
                    .catch(error => {
                        if (error.response.data.message) {
                            self.fetchUsers();
                        }
                    })
            },
            updateSelectedRows: function(selectedRows) {
                this.selectedRows = selectedRows;
            }
        }
    }
</script>
