<template>
    <sweet-modal blocking hide-close-button ref="modal" :title="cafe && cafe.name ? `Cafe : ${cafe.name}` : `New Cafe`">
        <div v-if="cafe" class="flex flex-col items-center justify-center">
            <div class="flex flex-col items-center justify-center w-full py-2">
                <label for="name" class="pb-3 text-xl">Give a name for the cafe</label>
                <text-input type="text" :value="cafe.name" @input="updateValue('name', $event)" placeholder="Name of the cafe..." />
            </div>
            <div v-show="cafe.name" class="flex flex-col items-center justify-center w-full py-2">
                <label for="name" class="pb-3 text-xl">Upload cafe logo</label>

                <div v-if="uploadingLogo" class="flex items-center justify-center w-full">
                    <label class="text-xl">Processing Image...</label>
                </div>
                <div v-else-if="cafe.newLogo && cafe.newLogo.secure_url" class="flex flex-col items-center justify-center w-full">
                    <img :src="cafe.newLogo.secure_url" class="object-cover w-24 h-24 mb-3 rounded-full" alt="">
                    <theme-button type="button" look="error" @pressed="clearLogo">
                        <div class="pr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        Clear
                    </theme-button>
                </div>
                <div v-else-if="cafe.logo" class="flex flex-col items-center justify-center w-full">
                    <img :src="cafe.logo" class="object-cover w-24 h-24 mb-3 rounded-full" alt="">
                    <theme-button type="button" look="error" @pressed="clearLogo">
                        <div class="pr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        Clear
                    </theme-button>
                </div>
                <div v-else class="flex items-center justify-center w-full">
                    <text-input type="file" @change="handleFileChange($event)" placeholder="Cafe logo..." />
                </div>
            </div>
            <div v-show="cafe.name && (cafe.logo || cafe.newLogo)" class="flex flex-col items-center justify-center w-full py-3 mt-3 border-t-2 border-gray-600">
                <div v-for="(tbl, tbl_idx) in cafe.tables" :key="`table_${tbl_idx}`" class="flex flex-col items-center justify-center w-full pb-4">
                    <div class="flex flex-row items-center justify-center">
                        <div v-for="(slot, idx) in tbl.slots" :key="`tbl_${tbl_idx}_top_slot_${idx}`" v-show="(Math.abs((idx + 1) % 2) == 1)" :class="['flex items-center justify-center w-12 h-12 m-2 rounded', slot.disallowed ? 'bg-red-700' : 'bg-green-700']">
                            <label v-if="slot.disallowed" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></label>
                            <label v-else class="text-white">{{ idx + 1 }}</label>
                        </div>
                    </div>
                    <div :class="['flex flex-col items-center justify-center m-2 bg-pink-500 rounded', Math.ceil(tbl.slots.length / 2) == 6 ? 'w-full' : `w-${Math.ceil(tbl.slots.length / 2)}/6`]">
                        <label class="m-2 text-xl text-white">Table {{ (tbl_idx + 1) }}</label>
                        <theme-button type="button" look="success" tailwindStyle="m-2" :disabled="tbl.slots.length >= 12" @pressed="addSlot(tbl_idx)">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </theme-button>
                        <theme-button type="button" look="danger" tailwindStyle="m-2" :disabled="tbl.slots.length <= 1" @pressed="removeSlot(tbl_idx)">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </theme-button>
                    </div>
                    <div class="flex flex-row items-center justify-center">
                        <div v-for="(slot, idx) in tbl.slots" :key="`tbl_${tbl_idx}_bottom_slot_${idx}`" v-show="((idx + 1) % 2 == 0)" :class="['flex items-center justify-center w-12 h-12 m-2 rounded', slot.disallowed ? 'bg-red-700' : 'bg-green-700']">
                            <label v-if="slot.disallowed" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></label>
                            <label v-else class="text-white">{{ idx + 1 }}</label>
                        </div>
                    </div>
                </div>
                <theme-button type="button" look="dark" @pressed="addTable">
                    <div class="pr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    Add Table
                </theme-button>
            </div>
        </div>
        <theme-button type="button" look="dark" slot="button" tailwindStyle="mr-2" @pressed="$emit('cancel-edit')">
            <div class="pr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            Cancel
        </theme-button>
        <theme-button type="button" look="success" slot="button" :disabled="!canSubmit" @pressed="saveCafe">
            <div class="pr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            Save
        </theme-button>
    </sweet-modal>
</template>

<script>
import { SweetModal } from 'sweet-modal-vue'
import TextInput from '../../Components/TextInput'
import ThemeButton from '../../Components/Button'

export default {
    props: ['open', 'cafe_index_route', 'cafe_prop'],
    components: {
        SweetModal,
        TextInput,
        ThemeButton
    },
    data() {
        return {
            cafe: this.cafe_prop,
            logo: null,
            uploadingLogo: false,
            disallowed: [2, 3, 6, 7, 10, 11]
        }
    },
    watch: {
        cafe_prop: function(val) {
            this.cafe = val
        },
        open: function(val) {
            if (val) {
                this.$refs.modal.open()
            } else {
                this.$refs.modal.close()
            }
        }
    },
    computed: {
        canSubmit: function() {
            return this.cafe != null && this.cafe.name != null && (this.cafe.logo == null || this.cafe.newLogo == null)
        }
    },
    methods: {
        updateValue: function(field, val) {
            this.$set(this.cafe, field, val)
        },
        handleFileChange: function(files) {
            this.upload(files[0])
        },
        upload: function(file) {
            let self = this;
            this.uploadingLogo = true;
            let reader = new FileReader();
            reader.addEventListener(
                "load",
                function() {
                    let formData = new FormData();
                    formData.append("upload_preset", 'cafe_booking');
                    formData.append("file", reader.result);
                   
                   let cloudinaryUploadURL = `https://api.cloudinary.com/v1_1/${process.env.MIX_CLOUDINARY_CLOUD_NAME}/upload`;
                    
                    let requestObj = {
                    url: cloudinaryUploadURL,
                        method: "POST",
                        data: formData,
                    };

                    var instance = axios.create();

                    delete instance.defaults.headers.common["X-Socket-Id"];

                    instance(requestObj)
                        .then(response => {
                            let result = response.data
                            self.cafe.newLogo = {
                                name: result.secure_url.split('/').pop(),
                                public_id: result.public_id,
                                type: `${result.resource_type}/${result.format}`,
                                secure_url: result.secure_url
                            }
                            self.uploadingLogo = false
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }.bind(this),
                false
            );
            
            reader.readAsDataURL(file);
        },
        clearLogo: function() {
            this.$set(this.cafe, 'logo', null)
            this.$set(this.cafe, 'newLogo', {})
        },
        addTable: function() {
            this.cafe.tables.push({ number: this.cafe.tables.length, slots: [
                { number: 1, disallowed: 0 }, { number: 2, disallowed: 1 }
            ], status_id: 1, status: { id: 1, name: 'Available', cssClass: 'bg-green-300 text-green-700' }})
        },
        addSlot: function(idx) {
            let n = this.cafe.tables[idx].slots.length + 1;
            this.cafe.tables[idx].slots.push({ number: n, disallowed: this.disallowed.includes(n) ? 1 : 0 })
        },
        removeSlot: function(idx) {
            this.cafe.tables[idx].slots.splice(0, 1)
        },
        saveCafe: function() {
            let self = this
            if (this.cafe.id) {
                axios.put(`${this.cafe_index_route}/${this.cafe.id}`, this.cafe)
                    .then(response => {
                        self.$emit('update-cafe', response.data)
                    })
            } else {
                axios.post(this.cafe_index_route, this.cafe)
                    .then(response => {
                        self.$emit('update-cafe', response.data, true)
                    })
            }
            this.$emit('cancel-edit')
        }
    }
}
</script>