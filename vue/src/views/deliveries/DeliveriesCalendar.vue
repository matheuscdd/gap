<template>
    <h1>Calendário</h1>
    <vCalendar
        :events="$store.state.deliveryMod.calendar.events"
        view-mode="month"
        text="hoje"
    >
        <template #event="{ event }">
            <vTooltip :text="event.description">
                <template v-slot:activator="{ props }">
                    <div v-bind="props"
                        class="v-calendar-weekly__day-alldayevents-container" 
                        @click="onEventClick(event)" 
                    >
                        <span class="v-chip v-chip--label v-theme--light text-primary v-chip--density-comfortable v-chip--size-default v-chip--variant-tonal" draggable="false" width="100%">
                            <span class="v-chip__underlay"></span>
                            <div class="v-chip__content" data-no-activator="">
                                <div class="v-badge v-badge--dot v-badge--inline">
                                    <div class="v-badge__wrapper">
                                        <span class="v-badge__badge v-theme--light" :style="{backgroundColor: event.color}" aria-atomic="true" aria-label="Distintivo" aria-live="polite" role="status"></span>
                                    </div>
                            </div>{{ event.name }}</div>
                        </span>
                    </div>
                </template>
            </vTooltip>
        </template>
    </vCalendar>
</template>

<script>
import { endpoints } from "@/common/consts";


export default {
    data: () => ({

    }),
    async beforeCreate() {
        window.scrollTo(0,0);
        await this.$store.dispatch("deliveryMod/storeCalendar");
    },
    methods: {
        onEventClick(event) {
            if (event.isHoliday) return;
            this.$router.push(endpoints.routes.DELIVERY_VIEW_FULL.replace(":id", event.redirectId));
        },
  },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-top: 20px;
}
</style>