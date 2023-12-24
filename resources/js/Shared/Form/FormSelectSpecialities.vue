<template>
    <FormSelect
        ref="selectSpecialities"
        v-bind="$attrs"
        searchable
        prefetch
        remote
        :remote-url="route('autocomplete.specialities')"
        full
        @selected="$emit('selected', $event)"
        placeholder="Выберите специальность"
    />
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectSpecialities",
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectSpecialitis.refreshData()
        }
    }
}
</script>
