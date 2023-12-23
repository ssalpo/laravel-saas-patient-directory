<template>
    <FormSelect
        ref="selectMedicalClinics"
        v-bind="$attrs"
        searchable
        prefetch
        remote
        :remote-url="route('autocomplete.medical_clinics')"
        full
        @selected="$emit('selected', $event)"
        placeholder="Выберите направившее учреждение"
    />
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectMedicalClinics",
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectMedicalClinics.refreshData()
        }
    }
}
</script>
