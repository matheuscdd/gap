<template>
    <aside v-show="this.$route.path !== endpoints.routes.LOGIN">
        <div class="container">
            <h2>Índice</h2>
            <ul>
                <li>
                    <h6>Comum</h6>
                    <RouterLink :to="endpoints.routes.HOME">Home</RouterLink> 
                </li>
                <li v-show="this.$store.state.userMod.logged.type === user.keys.TYPE.ADMIN">
                    <h6>Usuários</h6>
                    <RouterLink :to="endpoints.routes.USER_CREATE">Criar</RouterLink> 
                    <RouterLink :to="endpoints.routes.USER_LIST">Listar</RouterLink> 
                </li>
                <li>
                    <h6>Clientes</h6>
                    <RouterLink :to="endpoints.routes.CLIENT_CREATE">Criar</RouterLink> 
                    <RouterLink :to="endpoints.routes.CLIENT_LIST">Listar</RouterLink> 
                </li>
                <li>
                    <h6>Orçamentos</h6>
                    <RouterLink :to="endpoints.routes.BUDGET_CREATE">Criar</RouterLink> 
                    <RouterLink :to="endpoints.routes.BUDGET_LIST">Listar</RouterLink> 
                </li>
                <li>
                    <h6>Entregas</h6>
                    <RouterLink :to="endpoints.routes.DELIVERY_CREATE_FULL">Criar</RouterLink> 
                    <RouterLink :to="endpoints.routes.DELIVERY_LIST">Listar</RouterLink> 
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
};
</script>
<style scoped>
h2 {
    color: var(--green-2);
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;

}

aside {
    min-width: 9vw;
    margin-top: 25px;
    width: 9vw;
    height: 600px;
    min-width: 170px;
}

.container {
    margin-left: 20px;
    position: fixed;
    min-width: 125px;
    width: 6vw;
    border-right: 1px dashed var(--gray-1);
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