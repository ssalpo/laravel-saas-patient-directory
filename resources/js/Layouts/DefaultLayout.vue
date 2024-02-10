<template>
    <div class="wrapper">
        <Nav/>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container" :class="{'pt-4': $page.props.counts.sharedPatients}">

                <div class="alert alert-danger" v-if="$page.props.counts.sharedPatients" role="alert">
                    К вам обратились за консультацией. <a :href="route('patient-shares.index')">Нажмите чтобы увидеть</a>
                </div>

                <slot/>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>
</template>

<script>
import Nav from "../Shared/Nav.vue";

export default {
    components: {Nav}
}
</script>
