import { user as cUser, userForm, client as cClient, clientForm, budget as cBudget, budgetForm, delivery as cDelivery, deliveryForm, truck as cTruck, truckForm, maintenance as cMaintenance, maintenanceForm, driver as cDriver, driverForm } from "./consts";
import { base as limits } from "./validators";

export default {
    data: () => ({cUser, userForm, cClient, clientForm, limits, cBudget, budgetForm, cDelivery, deliveryForm, cTruck , truckForm, cMaintenance, maintenanceForm, cDriver, driverForm })
};