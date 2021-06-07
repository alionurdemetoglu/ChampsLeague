<template>
    <div class="container">
        <div class="row justify-content-center">
            <div>
                <nav>
                    <button href="#" @click="simulate()">Simulate</button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>

    import { EventBus } from '../event-bus.js';


    export default {
        name: 'League',
        methods: {
            simulate() {
                axios.post('/simulate')
                    .then((response)=>{
                        console.log(response.data);
                        EventBus.$emit('click::event', response.data);
                    });
            },
        }
    }
</script>
