import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";

export default createStore({
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
    }
});