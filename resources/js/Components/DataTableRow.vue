<template>
    <tr>
        <!-- <td class="px-3 border-t border-gray-200 border-dashed">
            <label
                class="inline-flex items-center justify-between px-2 py-2 text-teal-500 rounded-lg cursor-pointer hover:bg-gray-200">
                <input type="checkbox" class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline" :value="row.id.value" v-model="selectedRows"
                        @click="getRowDetail(row.id.value)">
            </label>
        </td> -->
        <td v-for="heading in headings" :key="heading.key" v-show="!heading.hidden" :class="['border-t border-gray-200 border-dashed', row[heading.key].cssClass ? row[heading.key].cssClass : '']">
            <select-box v-if="row[heading.key].dropdown" :value_prop="row[heading.key].value" :options_props="row[heading.key].options" @valueUpdated="rowUpdated(heading.key, $event)" />
            <div v-else-if="row[heading.key].type && row[heading.key].type == 'emojiList'" class="flex flex-row flex-wrap items-center justify-start">
                <div v-for="(emoji, index) in row[heading.key].display" :key="`emoji_${emoji.id}_${index}`" class="flex flex-row items-center justify-start w-1/3">
                    <label class="text-lg" v-html="`&#${emoji.dec};  ${emoji.name}`" />
                </div>
            </div>
            <text-span v-else class="flex items-center px-6 py-3 text-gray-700" :value_prop="row[heading.key].display" :editable="row[heading.key].editable" @valueUpdated="rowUpdated(heading.key, $event)" />
        </td>
        <td  v-if="actions && actions.length > 0" class="border-t border-gray-200 border-dashed">
            <div v-for="(action, idx) in actions" :key="`action_${idx}`" class="flex items-center justify-center w-full">
                <text-span v-if="action.ignoreIf && action.ignoreIf.values.includes(row[action.ignoreIf.name].value)" class="flex items-center px-6 py-3 text-gray-700" value_prop="-" />
                <theme-button v-else type="button" :look="action.look" class="mr-3" @pressed="$emit('actionTriggered', action.name)">{{ action.display }}</theme-button>
            </div>
        </td>
    </tr>
</template>

<script>
import SelectBox from './SelectBox';
import TextSpan from './TextSpan';
import ThemeButton from './Button'

export default {
    props: ['headings', 'row_prop', 'selectedRows_prop', 'actions'],
    components: {
        SelectBox,
        TextSpan,
        ThemeButton
    },
    data() {
        return {
            row: this.row_prop,
            selectedRows: this.selectedRows_prop
        }
    },
    watch: {
        row_prop: function(val) {
            this.row = val
        },
        selectedRows_prop: function(val) {
            this.selectedRows = val;
        }
    },
    methods: {
        getRowDetail(id) {
            let rows = this.selectedRows;
            if (rows.includes(id)) {
                let index = rows.indexOf(id);
                rows.splice(index, 1);
            } else {
                rows.push(id);
            }
            this.selectedRows = rows;
        },
        rowUpdated: function(key, payload) {
            let row = this.row;
            row[key].value = payload;

            let self = this;
            this.$nextTick(function() {
                this.$emit('rowUpdated', row);
            });
        }
    }
}
</script>