<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table v-if="simulations.length > 0" class="table">
                            <thead>
                            <tr>
                                <th scope="col">Week#</th>
                                <th scope="col">Home</th>
                                <th scope="col">Score</th>
                                <th scope="col">Away</th>
                            </tr>
                            </thead>
                            <tbody v-for="item in simulations">
                            <tr>
                                <td>{{item.week+1}}</td>
                                <td>{{item.homeTeam}}</td>
                                <td>{{item.homeScore}}-{{item.awayScore}}</td>
                                <td>{{item.awayTeam}}</td>
                            </tr>
                            </tbody>
                        </table>

                        <table v-if="teams.length > 0" class="table">
                            <thead>
                            <tr>
                                <th scope="col">Team</th>
                                <th scope="col">Point</th>
                                <th scope="col">Played</th>
                                <th scope="col">Win</th>
                                <th scope="col">Draw</th>
                                <th scope="col">Lose</th>
                                <th scope="col">Goal Difference</th>
                            </tr>
                            </thead>
                            <tbody v-for="item in teams">
                            <tr>
                                <td>{{item.name}}</td>
                                <td>{{item.point}}</td>
                                <td>{{item.played}}</td>
                                <td>{{item.win}}</td>
                                <td>{{item.draw}}</td>
                                <td>{{item.lose}}</td>
                                <td>{{item.goalDiff}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import { EventBus } from '../event-bus.js';
    
    export default {
        name: 'Simulations',
        data() {
            return {
                simulations: [],
                teams: [],
            }
        },
        methods: {
            change_n(n) {
                this.simulations = n[0];
                this.teams = n[1];
            },
        },
        created() {
            EventBus.$on("click::event", this.change_n);
        },
    }
</script>
