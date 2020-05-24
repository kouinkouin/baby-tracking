<template>
    <div>
        <Header/>
        <b-container class="mt-3">
            <router-view></router-view>
        </b-container>
    </div>
</template>

<script>
    import Header from './components/global/Header';

    export default {
        name: "app",
        components: {
            Header
        }
    }
</script>
