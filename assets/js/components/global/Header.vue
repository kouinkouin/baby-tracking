<template>
    <b-navbar variant="secondary" type="dark">
        <b-container>
            <b-navbar-brand href="#">Baby Tracker</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item :to="{name: 'add'}">Add</b-nav-item>
                    <b-nav-item :to="{name: 'history'}">History</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-container>
    </b-navbar>
</template>
