<template>
  <form @submit.prevent="emits('handleAutobanOptions',optionModel);loading = true">
    <div class="border-bottom text-center pb-3">
      {{ `${t(selectedAutoban.model.brand,'brand_title')} - ${t(selectedAutoban.model,'model_title')} - ${t(selectedAutoban.type,'type_title')} - ${t(selectedAutoban.year,'year_number')}` }}
      <Image width="100" :src="selectedAutoban.model.model_image" preview />
    </div>
    <div class="border-bottom text-center pb-3">
      <div class="form-floating my-1">
        <Dropdown :options="autobansByBrand.data" placeholder="Similar Option"
                  v-model="selectedAutobanOption" show-clear
                  @change="autobanOptionFunction(selectedAutobanOption)"
                  filter filter-placeholder="Search"
                  :option-label="opt=>`${t(opt.model.brand,'brand_title')} - ${t(opt.model,'model_title')} - ${t(opt.type,'type_title')} - ${t(opt.year,'year_number')}`"
                  class="form-control d-flex align-items-stretch" />
        <label> Similar Option </label>
      </div>
    </div>
    <Loading v-if="optionClasses.loading" />
    <div v-if="!optionClasses.loading" class="border-bottom">
      <step-progress icon-class="fa fa-check"
                     :steps="steps" :current-step="currentStep"
                     active-color="green" :active-thickness="5"
                     passive-color="gray" :passive-thickness="2"
                     :line-thickness="4" />
    </div>
    <div v-if="!optionClasses.loading" class="card-body">
      <Loading v-if="optionSubClass.loading || autobanOption.loading" />
      <div v-else class="row">
        <div v-if="currentStep === optionClasses.data.length">
          <OptionsPreview :optionModel="optionModel" />
        </div>
        <div v-else v-for="subClass in optionSubClass.data" class="col-6 text-center">
          {{ t(subClass,'option_sub_class_title') }}
          <div class="row text-start">
            <div v-for="category in subClass.option_categories" class="col-12">
              <div
                v-if="!category.input_type.includes('select')"
                class="form-floating my-1"
                :class="[optionModel[category.id] || 'border-danger border']"
              >
                <input v-model="optionModel[category.id]"
                       class="form-control"
                       step="0.01"
                       :type="category.input_type"
                       :placeholder="t(category, 'option_category_title')">
                <label> {{ t(category, 'option_category_title') }} </label>
              </div>
              <div v-if="category.input_type.includes('select')" class="form-floating my-1" :class="[optionModel[category.id] || 'border-danger border']">
                <MultiSelect v-model="optionModel[category.id]"
                             v-if="category.input_type.includes('multiple')"
                             :selectAll="false"
                             :options="category.options"
                             filter filter-placeholder="Search"
                             option-value="id" :option-label="opt=>t(opt,'option_title')"
                             class="form-control d-flex align-items-stretch"
                             :placeholder="t(category,'option_category_title')" />
                <Dropdown v-model="optionModel[category.id]"
                          v-else :options="category.options"
                          filter filter-placeholder="Search"
                          option-value="id" show-clear
                          :option-label="opt=>t(opt,'option_title')"
                          class="form-control d-flex align-items-stretch"
                          :placeholder="t(category, 'option_category_title')" />
                <label> {{ t(category, 'option_category_title') }} </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="!optionClasses.loading" class="card-footer d-flex justify-content-between">
      <div>
        <button type="button"
                class="btn btn-danger"
                v-if="currentStep"
                :disabled="optionSubClass.loading"
                @click="currentStep--"> prev </button>
      </div>
      <div>
        <button type="button"
                class="btn btn-success"
                v-if="currentStep !== optionClasses.data.length"
                :disabled="optionSubClass.loading"
                @click="currentStep++"> Next </button>
        <button type="submit" class="btn btn-success ms-2" :class="[!loading || 'btn-loading']"> Finish </button>
      </div>
    </div>
  </form>
</template>

<script setup>
import 'vue-step-progress/dist/main.css';
import StepProgress from 'vue-step-progress';
import Loading from "../../components/Loading.vue";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import Image from "primevue/image";

import _debounce from "lodash/debounce"
import OptionsPreview from "./OptionsPreview.vue";
import {computed, inject, onMounted, ref, watch} from "vue";
import {useAutobanOptionsStore} from "../../store/AutobanOptions";
import {useOptionStore} from "../../store/Options";
import {useAutobanStore} from "../../store/Autoban";

const props = defineProps({
  "selectedAutoban": Object,
  "optionTree": {
    type: Boolean,
    default: true
  },
  "step": {
    type: Number,
    default: 0
  }
})
const emits = defineEmits([
  'autobansByBrandOption',
  'handleAutobanOptions',
])
const selectedAutobanOption = ref(null);
const t = inject('t')

const AutobanOptionsStore = useAutobanOptionsStore()
const OptionStore = useOptionStore();
const optionClasses = computed(()=>OptionStore.optionClasses)
const steps = computed(()=>[...OptionStore.optionClasses.data.map(x=>t(x,'option_class_title')),'Preview'])
const currentStep = ref(null)
// currentStep.value = 0

const AutobanStore = useAutobanStore()
const autobansByBrand = computed(()=>AutobanStore.autobansByBrand)
const optionSubClass = computed(()=>OptionStore.optionSubClass)
const autobanOption = computed(()=>AutobanOptionsStore.autobanOption)
const optionModel = ref({})

const autobanOptionFunction = async (autoban = false) => {
  optionModel.value = {}
  await AutobanOptionsStore.initAutobanOption(autoban || props.selectedAutoban)
  for (let i = 0; i < autobanOption.value.data.pivots.length; i++) {
    const x = autobanOption.value.data.pivots[i];
    optionModel.value[+x.category.id] = (x.options.length ? (x.category.input_type === 'select' ? x.options[0].id : x.options.map(z=>+z.id)) : x.option)
  }
}

onMounted(_debounce(async ()=>{
  if ( props.optionTree ) {
    await OptionStore.initOptionClasses()
  }
  /*currentStep.value = 0*/
  currentStep.value = props.step
  AutobanStore.initAutobansByBrand(props.selectedAutoban)
  autobanOptionFunction()
},0))

const errors = ref([])
const loading = ref(false)

watch(
  ()=>currentStep.value,
  async (val)=>{
    const optionClass = optionClasses.value.data[val]
    if ( optionClass ) {
      await OptionStore.initOptionSubClassWithChildrens(optionClass)
    }
    const step = document.getElementsByClassName('step-progress__step');
    for (let i = 0; i < step.length; i++) {
      const item = step[i];
      item.onclick = (e)=>{
        currentStep.value = (+e.currentTarget.querySelector('span').innerHTML - 1)
      }
    }
  }
)

</script>
