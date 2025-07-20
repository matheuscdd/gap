<template>
    <div class="aside-space" v-show="showAside()"></div>
    <aside v-if="showAside()">
        <div class="container">
            <h2>Índice</h2>
            <ul>
                <li>
                    <h6>Comum</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.HOME)" 
                        :to="endpoints.routes.HOME"
                    >Home</RouterLink> 
                </li>
                <li v-show="$store.state.userMod.logged.type === user.keys.TYPE.ADMIN">
                    <h6>Usuários</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.USER_CREATE)" 
                        :to="endpoints.routes.USER_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.USER_EDIT)"
                        :class="setColor(endpoints.names.USER_EDIT)" 
                    >Editar</a> 
                    <RouterLink 
                        :class="setColor(endpoints.names.USER_LIST)" 
                        :to="endpoints.routes.USER_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Clientes</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.CLIENT_CREATE)" 
                        :to="endpoints.routes.CLIENT_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.CLIENT_EDIT)"
                        :class="setColor(endpoints.names.CLIENT_EDIT)" 
                    >Editar</a> 
                    <RouterLink 
                        :class="setColor(endpoints.names.CLIENT_LIST)" 
                        :to="endpoints.routes.CLIENT_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Motoristas</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.DRIVER_CREATE)" 
                        :to="endpoints.routes.DRIVER_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.DRIVER_EDIT)"
                        :class="setColor(endpoints.names.DRIVER_EDIT)" 
                    >Editar</a> 
                    <RouterLink 
                        :class="setColor(endpoints.names.DRIVER_LIST)" 
                        :to="endpoints.routes.DRIVER_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Orçamentos</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.BUDGET_CREATE)" 
                        :to="endpoints.routes.BUDGET_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.BUDGET_VIEW)"
                        :class="setColor(endpoints.names.BUDGET_VIEW)" 
                    >Visualizar</a> 
                    <a v-show="isInPage(endpoints.names.BUDGET_EDIT)"
                        :class="setColor(endpoints.names.BUDGET_EDIT)" 
                    >Editar</a> 
                    <RouterLink 
                        :class="setColor(endpoints.names.BUDGET_LIST)" 
                        :to="endpoints.routes.BUDGET_LIST"
                    >Listar</RouterLink> 
                </li>
                <li>
                    <h6>Entregas</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.DELIVERY_CREATE_FULL)" 
                        :to="endpoints.routes.DELIVERY_CREATE_FULL"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.DELIVERY_EDIT_FULL)"
                        :class="setColor(endpoints.names.DELIVERY_EDIT_FULL)" 
                    >Editar</a> 
                    <a v-show="isInPage(endpoints.names.DELIVERY_VIEW_FULL)"
                        :class="setColor(endpoints.names.DELIVERY_VIEW_FULL)" 
                    >Visualizar</a> 
                    <RouterLink 
                        :class="setColor(endpoints.names.DELIVERY_LIST)" 
                        :to="endpoints.routes.DELIVERY_LIST"
                    >Listar</RouterLink> 
                    <RouterLink 
                        :class="setColor(endpoints.names.DELIVERY_DASH)" 
                        :to="endpoints.routes.DELIVERY_DASH"
                    >Dash</RouterLink> 
                    <RouterLink 
                        :class="setColor(endpoints.names.DELIVERY_CALENDAR)" 
                        :to="endpoints.routes.DELIVERY_CALENDAR"
                    >Calendário</RouterLink> 
                </li>
                <li>
                    <h6>Caminhões</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.TRUCK_CREATE)" 
                        :to="endpoints.routes.TRUCK_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.TRUCK_EDIT)"
                        :class="setColor(endpoints.names.TRUCK_EDIT)" 
                    >Editar</a> 
                </li>
                <li>
                    <h6>Manutenções</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.MAINTENANCE_CREATE)" 
                        :to="endpoints.routes.MAINTENANCE_CREATE"
                    >Criar</RouterLink> 
                    <a v-show="isInPage(endpoints.names.MAINTENANCE_EDIT)"
                        :class="setColor(endpoints.names.MAINTENANCE_EDIT)" 
                    >Editar</a> 
                </li>
                <li>
                    <h6>Garagem</h6>
                    <RouterLink 
                        :class="setColor(endpoints.names.GARAGE_LIST)" 
                        :to="endpoints.routes.GARAGE_LIST"
                    >Listar</RouterLink> 
                    <RouterLink 
                        :class="setColor(endpoints.names.GARAGE_DASH)" 
                        :to="endpoints.routes.GARAGE_DASH"
                    >Dash</RouterLink> 
                </li>
            </ul>
        </div>
    </aside>
</template>
<script>
import { endpoints, user } from "@/common/consts";
import { jwtDecode } from "@/common/utils";
import { RouterLink } from "vue-router";


export default {
    data: () => ({
        endpoints,
        user,
        
    }),
    components: {
        RouterLink,
    },
    methods: {
        setColor(name) {
            return this.isInPage(name) ? "active" : "";
        },
        isInPage(name) {
            return this.$route.name === name;
        },
        showAside() {
            const now = new Date().getTime() / 1000;
            const canPage = ![endpoints.names.LOGIN, endpoints.names.USER_PASSWORD_LOST, endpoints.names.USER_PASSWORD_RESET ].includes(this.$route.name);
            const token = localStorage.getItem("token");
            const canToken = token ? now < jwtDecode(token).exp : false;
            return canToken && canPage;
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
    background-color: rgba(255, 255, 255, 0.17);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;
    border: 2px rgba(255,255,255,0.4) solid;
    border-bottom: 2px rgba(40,40,40,0.35) solid;
    border-right: 2px rgba(40,40,40,0.35) solid;
    user-select: none;
}

.container {
    margin-left: 20px;
    position: fixed;
    min-width: 125px;
    width: 7vw;
}

ul {
    overflow-y: scroll;
    height: 75vh;
}

ul::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #F5F5F5;
}

ul::-webkit-scrollbar {
	width: 5px;
	background-color: #F5F5F5;
}

ul::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #555;
}

h6, a {
    margin-bottom: 9px;
}

a { 
    display: block;
    color: inherit;
    text-decoration: none;
    margin-left: 20px;
    border-bottom: 2px solid transparent;
    width: max-content;
    padding-bottom: 5px;
    color: var(--black-1);
} 

.active, a:hover {
    border-bottom-color: var(--yellow-2);
    color: var(--yellow-2);
    -webkit-text-stroke: 0.5px var(--yellow-2);
}

a::before {
    content: "- ";
}

</style>