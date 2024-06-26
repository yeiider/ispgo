<template>
    <div>
        <label :for="fieldId" class="inline-block leading-tight space-x-1">
            <span>{{ field.name }}</span>
            <span v-if="field.required" class="text-red-500 text-sm">*</span>
        </label>
        <textarea
            :id="fieldId"
            :placeholder="field.placeholder"
            class="w-full form-control form-input form-control-bordered"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            rows="5"
        ></textarea>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: String,
        field: Object
    },
    computed: {
        fieldId() {
            return `${this.field.attribute}-textarea-field`;
        }
    }
}
</script>
