<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Cancel Waiting
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-15">
                        <div v-if="successText" class="flex flex-col items-center justify-center w-full">
                            <div class="text-green-700">
                                <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </div>
                            <label class="text-4xl">{{ successText }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
    
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    
    export default {
        props: ['cafe_id', 'cancel_waiting_route'],
        components: {
            AppLayout
        },
        data() {
            return {
                successText: null,
            }
        },
        mounted() {
            this.cancelWaiting();
        },
        methods: {
            cancelWaiting: function() {
                let self = this
                axios.get(`${this.cancel_waiting_route}`)
                    .then(response => {
                        self.successText = response.data.success
                    })
            }
        }
    }
</script>