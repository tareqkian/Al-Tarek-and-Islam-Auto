<template>
  <PageLayout :meta="this.$route.meta">

    <div class="card">
      <div class="card-body">
        <autoban-table
          type="options"
          :autoban="autoban"
          :filters="filters"
          :filter="filter"
          @page="AutobanStore.initAutobans" />
      </div>
    </div>

    <div class="card">
      <div class="border-bottom">
        <Loading v-if="optionClasses.loading" />
        <step-progress v-else icon-class="fa fa-check"
                       :steps="steps" :current-step="currentStep"
                       active-color="green" :active-thickness="5"
                       passive-color="gray" :passive-thickness="2"
                       :line-thickness="4" />
      </div>
      <div class="card-body">
        <h1 class="text-center">{{ steps[currentStep] }}</h1>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div>
          <button class="btn btn-danger" v-if="currentStep" @click="currentStep--"> prev </button>
        </div>
        <div>
          <button class="btn btn-success" v-if="currentStep !== optionClasses.data.map(x=>t(x,'option_class_title')).length" @click="currentStep++"> Next </button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <pre style="max-height: 400px; overflow: auto">{{autoban}}</pre>
      </div>
    </div>

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import AutobanTable from "../../components/Autoban/AutobanTable.vue";
import Loading from "../../components/Loading.vue";
import StepProgress from 'vue-step-progress';
import {computed, inject, ref, watch} from "vue";
import 'vue-step-progress/dist/main.css';

import {useOptionStore} from "../../store/Options";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";

const t = inject('t')

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const filter = ref('')

const AutobanStore = useAutobanStore()
const autoban = computed(()=>AutobanStore.autobans)
AutobanStore.initAutobans()








const OptionStore = useOptionStore();
const optionClasses = computed(()=>OptionStore.optionClasses)
const steps = computed(()=>[...OptionStore.optionClasses.data.map(x=>t(x,'option_class_title')),'Preview'])
OptionStore.initOptionClasses()
const currentStep = ref(0)

</script>

<style>
.step-progress__step:after {
  height: 50px !important;
  width: 50px !important;
}
.step-progress__step-label{
  top: calc(100% + 15px) !important;
}
.step-progress__step span{
  font-size: 35px !important;
}
.rtl .step-progress__wrapper-after {
  transform-origin: right center;
}
</style>
