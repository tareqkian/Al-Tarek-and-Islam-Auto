<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <Loading v-if="pricelistBrands.loading" />
            <DataView v-else :value="pricelistBrands.data" layout="grid">
              <template #grid="val">
                <div class="col-3 my-4 text-center">
                  <Image @click="toggle($event,val.data)"
                         class="pointer" imageStyle="width: 200px"
                         :src="val.data.brand_image" />
                  <span class="d-block mt-2 font-weight-bold">{{ t(val.data,'brand_title') }}</span>
                </div>
              </template>
            </DataView>
          </div>
        </div>
      </div>
    </div>

    <OverlayPanel ref="op" :breakpoints="{'960px': '75vw', '640px': '90vw'}"
                  :showCloseIcon="true" :style="{width: '450px'}" :dismissable="false">
      <Loading v-if="pricelistModels.loading" />
      <div v-if="!pricelistModels.loading" class="card-header pt-0 pb-4">
        <Image imageStyle="width: 60px" :src="pricelistModels.data.brand_image" />
        <h4 class="mx-2 my-0">{{ t(pricelistModels.data,'brand_title') }}</h4>
      </div>
      <div v-if="!pricelistModels.loading" class="card-body py-2" style="max-height: 405px !important;overflow: auto">
        <div class="accordion" id="accordionFlush">
          <div v-for="model in pricelistModels.data.models" :key="model.id" class="py-3 border-bottom">
            <div class="row justify-content-center px-3"
                 data-bs-toggle="collapse"
                 :data-bs-target="`#flush-collapse${model.id}`">
              <div class="col-6 text-center">
                <Image imageStyle="width: 120px" :src="model.model_image" />
                <span class="d-block mt-2 font-weight-bold">{{ t(model,'model_title') }}</span>
              </div>
              <div class="col-6 text-center">
                <span>Test Text</span>
              </div>
            </div>
            <div :id="`flush-collapse${model.id}`" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
              <div class="accordion-body">
                <ol class="mb-0">
                  <li v-for="autoban in model.autobans" class="mb-5">
                    <div class="row">
                      <div class="col-12">
                        <input type="checkbox" :value="autoban" v-model="autobans">
                        {{ `${t(autoban.type,'type_title')} - ${t(autoban.year,'year_number')}` }}
                        <i @click="autobanOptionDialog(autoban)" class="fa fa-edit px-3"></i>
                      </div>
                      <div class="col">{{ t(autoban.price,'official',null,true) }}</div>
                      <div class="col">{{ t(autoban.price,'commercial',null,true) }}</div>
                      <div class="col">{{ t(autoban.price,'sale',null,true) }}</div>
                    </div>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </div>
    </OverlayPanel>

    <Dialog
      modal dismissableMask
      class="modal-content modal-lg" content-class="modal-body"
      :showHeader="false" v-model:visible="autobanOptionDialogShow">
      <div class="card-body">
        <OptionsPreview :autoban="selectedAutoban" :optionModel="optionModel" />
      </div>
    </Dialog>

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import DataView from 'primevue/dataview';
import Dropdown from "primevue/dropdown";
import DataViewLayoutOptions from "primevue/dataviewlayoutoptions"
import Image from "primevue/image";
import OverlayPanel from 'primevue/overlaypanel';
import Loading from "../../components/Loading.vue";
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Dialog from "primevue/dialog";
import OptionsPreview from '../../components/Option/OptionsPreview.vue'

import {usePricelistStore} from "../../store/Pricelist";
import {computed, ref, watch} from "vue";
import {useAutobanOptionsStore} from "../../store/AutobanOptions";
import {useComparisonStore} from "../../store/AutobanOptionComparison";

const pricelistStore = usePricelistStore()
const pricelistBrands = computed(()=>pricelistStore.pricelistBrands)
pricelistStore.initPricelistBrands()

const pricelistModels = computed(()=>pricelistStore.pricelistModels)
const op = ref();
const toggle = async (event,data) => {
  await op.value.hide();
  pricelistStore.initPricelistModels(data)
  op.value.toggle(event,event.target);
};


const AutobanOptionsStore = useAutobanOptionsStore();
const autobanOption = computed(()=>AutobanOptionsStore.autobanOption)

const errors = ref([])
const loading = ref(false)
const autobanOptionDialogShow = ref(false);
const selectedAutoban = ref(null);
const optionModel = ref(null);

const autobanOptionDialog = async (autoban)=>{
  errors.value = []
  autobanOptionDialogShow.value = !autobanOptionDialogShow.value
  selectedAutoban.value = autoban
  optionModel.value = {}
  await AutobanOptionsStore.initAutobanOption(autoban || props.selectedAutoban)
  for (let i = 0; i < autobanOption.value.data.pivots.length; i++) {
    const x = autobanOption.value.data.pivots[i];
    optionModel.value[+x.category.id] = (x.options.length ? (x.category.input_type === 'select' ? x.options[0].id : x.options.map(z=>+z.id)) : x.option)
  }
}


const ComparisonStore = useComparisonStore()
const autobans = computed({
  get: () => ComparisonStore.autobans,
  set: (val) => ComparisonStore.autobans = val
})
</script>

<style scoped>
:deep(.p-overlaypanel) {
  padding: 150px !important;
}
</style>
