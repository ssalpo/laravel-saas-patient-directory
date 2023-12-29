<template>

    <div class="common-layout">
        <el-container>
            <el-header>
                <Nav/>
            </el-header>
            <el-main>
                <slot />
            </el-main>
        </el-container>
    </div>
</template>

<script>
import {ElHeader, ElMain} from "element-plus";

import Nav from "../Shared/Nav.vue";

export default {
    components: {Nav, ElHeader, ElMain}
}
</script>
