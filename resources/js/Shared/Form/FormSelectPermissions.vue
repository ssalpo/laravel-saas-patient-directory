<template>
    <div class="form-group">
        <label class="form-asterisk">Права</label>
        <div class="custom-control custom-checkbox" v-for="permission in permissions">
            <input class="custom-control-input"
                   :value="permission.id"
                   :checked="modelValue.includes(permission.id)"
                   type="checkbox"
                   @change="$emit('update:modelValue', updateValue($event, permission))"
                   :id="`permissionCheckbox${permission.id}`">

            <label :for="`permissionCheckbox${permission.id}`" class="custom-control-label">
                {{ permission.text }}
            </label>

        </div>

        <div v-if="validationError" class="invalid-feedback-simple">
            {{ validationError }}
        </div>
    </div>
</template>

<script>
import {without} from "lodash/array";

export default {
    name: "FormSelectPermissions",
    props: {
        modelValue: Array,
        validationError: String
    },
    data() {
        return {
            permissions: []
        }
    },
    created() {
        this.loadPermissions();
    },
    methods: {
        updateValue(e, permission) {
            if(!e.currentTarget.checked) {
                return without(this.modelValue, permission.id)
            }

            return [...this.modelValue, permission.id];
        },
        loadPermissions() {
            axios.get(route('autocomplete.permissions'))
                .then((r) => this.permissions = r.data)
        },
    }
}
</script>
