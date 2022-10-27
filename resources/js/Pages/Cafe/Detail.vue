<template>
    <sweet-modal blocking hide-close-button ref="modal" title="Cafe Details">
        <div v-if="cafe" class="flex flex-col items-center justify-center">
            <div class="flex flex-col items-center justify-center w-full py-2">
                <label class="pb-3 text-2xl">{{ cafe.name }}</label>
                <img :src="cafe.logo" class="object-cover w-24 h-24 mb-3 rounded-full" alt="">
                <div v-if="user.type_id == 2 && user.id == cafe.owner_id" class="flex items-center justify-center w-full">
                    <theme-button type="button" look="dark" slot="button" tailwindStyle="mr-2" @pressed="$emit('edit-cafe', cafe)">
                        <div class="pr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        Edit Cafe
                    </theme-button>
                    <theme-button type="button" look="dark" @pressed="generateQR()">
                        <div class="pr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        Download QR
                    </theme-button>
                </div>
                <div class="flex flex-row items-center justify-center pt-3">
                    <div v-for="status in cafe_table_statusses" :key="`cafe_status_${status.id}`" :class="['m-2 p-4 rounded', status.cssClass]">
                        <label class="font-bold">{{ status.name }}</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center w-full py-2">
                <div v-for="(tbl, tbl_idx) in cafe.tables" :key="`table_${tbl_idx}`" class="flex flex-col items-center justify-center w-full py-4 border-t border-pink-700">
                    <div class="flex flex-row items-center justify-center">
                        <div v-for="(slot, idx) in tbl.slots" :key="`tbl_${tbl_idx}_top_slot_${idx}`" v-show="(Math.abs((idx + 1) % 2) == 1)" :class="['flex items-center justify-center w-12 h-12 m-2 rounded', slot.disallowed ? 'bg-red-700' : tbl.status.cssClass]">
                            <label v-if="slot.disallowed" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></label>
                            <label v-else class="font-bold">{{ idx + 1 }}</label>
                        </div>
                    </div>
                    <div :class="['flex flex-col items-center justify-center m-2 rounded', Math.ceil(tbl.slots.length / 2) == 6 ? 'w-full' : `w-${Math.ceil(tbl.slots.length / 2)}/6`, tbl.status.cssClass]">
                        <label class="m-2 text-xl">Table {{ (tbl_idx + 1) }}</label>
                    </div>
                    <div class="flex flex-row items-center justify-center">
                        <div v-for="(slot, idx) in tbl.slots" :key="`tbl_${tbl_idx}_bottom_slot_${idx}`" v-show="((idx + 1) % 2 == 0)" :class="['flex items-center justify-center w-12 h-12 m-2 rounded', slot.disallowed ? 'bg-red-700' : tbl.status.cssClass]">
                            <label v-if="slot.disallowed" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></label>
                            <label v-else class="font-bold">{{ idx + 1 }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <theme-button type="button" look="dark" slot="button" :tailwindStyle="user == 3 ? 'mr-2' : ''" @pressed="$emit('cancel-edit')">
            <div class="pr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            Close
        </theme-button>
        <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="false"
            :paginate-elements-by-height="1400"
            filename="Cafe QR Code"
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="100%"
            ref="html2Pdf"
            :cafe="cafe"
        >
            <cafe-pdf v-if="this.cafe" slot="pdf-content" :cafe="cafe" :cafe_index_route="cafe_index_route" />
        </vue-html2pdf>
    </sweet-modal>
</template>

<script>
import { SweetModal } from 'sweet-modal-vue'
import TextInput from '../../Components/TextInput'
import ThemeButton from '../../Components/Button'
import VueHtml2pdf from 'vue-html2pdf'
import CafePdf from './Pdf'

export default {
    props: ['open', 'cafe_index_route', 'cafe_prop', 'user', 'cafe_table_statusses'],
    components: {
        SweetModal,
        TextInput,
        ThemeButton,
        VueHtml2pdf,
        CafePdf
    },
    data() {
        return {
            cafe: this.cafe_prop,
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
        
    },
    methods: {
        generateQR: function() {
            this.$refs.html2Pdf.generatePdf()
        }
    }
}
</script>