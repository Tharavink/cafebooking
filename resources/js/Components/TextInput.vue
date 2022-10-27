<template>
    <input v-if="type == 'file'" :type="type" class="w-full rounded-md shadow-sm form-input overflow-ellipsis" @change="$emit('change', $event.target.files)" ref="input" :placeholder="placeholder">
    <input v-else :type="type" class="w-full rounded-md shadow-sm form-input" :value="value" :min="min" :max="max" @keyup="$emit('input', $event.target.value)" ref="input" :placeholder="placeholder">
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            default: null,
        },
        editable: {
            type: Boolean,
            default: true
        },
        type: {
            type: String,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: 'Type here...'
        },
        min: {
            type: String,
            default: null,
        },
        max: {
            type: String,
            default: null,
        }
    }
}
</script>