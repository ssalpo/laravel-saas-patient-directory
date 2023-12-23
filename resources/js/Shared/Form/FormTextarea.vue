<script>
import {defineComponent} from 'vue'

export default defineComponent({
    props: {
        label: {
            type: String,
            required: false
        },
        required: {
            type: Boolean,
            default: false
        },
        validationError: String,
        modelValue: String
    }
})
</script>

<template>
    <div class="form-group">
        <label :class="{'form-asterisk': required}" v-show="label">{{ label }}</label>

        <textarea :class="{'is-invalid': validationError}"
                  :value="modelValue"
                  v-bind="$attrs"
                  class="form-control"
                  @input="$emit('update:modelValue', $event.target.value)"
        ></textarea>

        <div v-if="validationError" class="error invalid-feedback">
            {{ validationError }}
        </div>
    </div>
</template>

