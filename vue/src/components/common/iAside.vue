<template>
    <div class="aside-space"></div>
    <aside v-show="this.$route.path !== endpoints.routes.LOGIN">
        <div class="container">
            <h2>Índice</h2>
            <ul>
                <li>
                    <h6>Comum</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.HOME)" 
                        :to="endpoints.routes.HOME"
                    >Home</RouterLink> 
                </li>
                <li v-show="this.$store.state.userMod.logged.type === user.keys.TYPE.ADMIN">
                    <h6>Usuários</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.USER_CREATE)" 
                        :to="endpoints.routes.USER_CREATE"
                    >Criar</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.USER_LIST)" 
                        :to="endpoints.routes.USER_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Clientes</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.CLIENT_CREATE)" 
                        :to="endpoints.routes.CLIENT_CREATE"
                    >Criar</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.CLIENT_LIST)" 
                        :to="endpoints.routes.CLIENT_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Orçamentos</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.BUDGET_CREATE)" 
                        :to="endpoints.routes.BUDGET_CREATE"
                    >Criar</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.BUDGET_LIST)" 
                        :to="endpoints.routes.BUDGET_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Entregas</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.DELIVERY_CREATE_FULL)" 
                        :to="endpoints.routes.DELIVERY_CREATE_FULL"
                    >Criar</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.DELIVERY_LIST)" 
                        :to="endpoints.routes.DELIVERY_LIST"
                    >Listar</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.DELIVERY_DASH)" 
                        :to="endpoints.routes.DELIVERY_DASH"
                    >Dash</RouterLink> 
                    <RouterLink 
                        :style="setColor(endpoints.routes.DELIVERY_CALENDAR)" 
                        :to="endpoints.routes.DELIVERY_CALENDAR"
                    >Calendário</RouterLink> 
                </li>
                <li>
                    <h6>Caminhões</h6>
                    <RouterLink 
                        :style="setColor(endpoints.routes.TRUCK_CREATE)" 
                        :to="endpoints.routes.TRUCK_CREATE"
                    >Criar</RouterLink> 
                </li>
            </ul>
        </div>
    </aside>
</template>
<script>
import { endpoints, user } from "@/common/consts";
import { RouterLink } from "vue-router";


export default {
    data: () => ({
        endpoints,
        user
    }),
    components: {
        RouterLink
    },
    methods: {
        setColor(path) {
            return {color: this.$route.path === path ? "var(--green-2)" : "var(--black-1)"};
        }
    }
};
</script>
<style scoped>
h2 {
    color: var(--green-2);
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;

}

.aside-space {
    min-width: 200px;
}

aside {
    padding-top: 25px;
    height: 100vh;
    min-width: 170px;
    position: fixed;
    backdrop-filter: blur(5px);
    background-color: rgba(0, 255, 76, 0.075);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;
    border: 2px rgba(255,255,255,0.4) solid;
    border-bottom: 2px rgba(40,40,40,0.35) solid;
    border-right: 2px rgba(40,40,40,0.35) solid;
}

.container {
    margin-left: 20px;
    position: fixed;
    min-width: 125px;
    width: 6vw;
}

h6, a {
    margin-bottom: 12px;
}

a { 
    display: block;
    color: inherit;
    text-decoration: none;
    margin-left: 20px;
} 

a:hover {
    color: var(--green-2);
}

a::before {
    content: "- ";
}

</style>