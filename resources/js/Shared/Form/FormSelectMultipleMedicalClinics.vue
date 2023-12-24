<script>
import Multiselect from '@vueform/multiselect'
import {without} from "lodash/array";

export default {
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
        modelValue: Array
    },
    emits: ['update:modelValue'],
    components: {Multiselect},
    data() {
        return {
            selected: this.modelValue,
            items: []
        }
    },
    created() {
        this.getMedicalClinics()
    },
    methods: {
        getMedicalClinics: function (q) {
            axios.get(route('autocomplete.medical_clinics') + `?q=${q || ''}`)
                .then((r) => this.items = r.data)
        }
    },
    watch: {
        selected(v) {
            this.$emit('update:modelValue', v)
        }
    }
}
</script>

<template>
    <div class="form-group">
        <label :class="{'form-asterisk': required}" v-show="label">{{ label }}</label>

        <select
            v-model="selected"
            :class="{'is-invalid': validationError}"
            multiple
            class="form-control"
        >
            <option :value="item.id" :selected="modelValue.includes(item.id)" v-for="item in items">
                {{ item.text }}
            </option>
        </select>

        <div v-if="validationError" class="error invalid-feedback">
            {{ validationError }}
        </div>
    </div>
</template>
