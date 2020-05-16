<template>
    <div id="app">
        <Header/>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-2"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from './components/global/Header';
    export default {
        data() {
            return {};
        },
        name: 'app',
        components: {
            Header
        }
    }
</script>
