<template>
  <PageLayout :meta="this.$route.meta">
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

    <OverlayPanel ref="op"
                  :showCloseIcon="true"
                  :style="{width: '450px'}"
                  :breakpoints="{'960px': '75vw', '640px': '90vw'}">
      <Loading v-if="pricelistModels.loading" />
      <div v-if="!pricelistModels.loading" class="card-header pt-0 pb-4">
        <Image imageStyle="width: 60px" :src="pricelistModels.data.brand_image" />
        <h4 class="mx-2 my-0">{{ t(pricelistModels.data,'brand_title') }}</h4>
      </div>
      <div v-if="!pricelistModels.loading" class="card-body py-2 px-0">

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
                      <div class="col-12">{{ `${t(autoban.type,'type_title')} - ${t(autoban.year,'year_number')}` }}</div>
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

import {usePricelistStore} from "../../store/Pricelist";
import {computed, onBeforeMount, ref} from "vue";
import {useAutobanStore} from "../../store/Autoban";

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

/*
const pricelistAutobans = computed(()=>pricelistStore.pricelistAutobans)
const autobanShow = ref(false)
const autobanDialog = (model) => {
  autobanShow.value = !autobanShow.value
  pricelistStore.initPricelistAutobans(model)
}
*/
const loading = ref(false)

Echo.channel("PricelistsEvent")
  .listen('AutobanAdder',({autoban})=>{
    pricelistStore.pricelistBrands.data = [...pricelistStore.pricelistBrands.data, autoban.model.brand]
  })
  .listen('AutobanEditor',({autoban})=>{
    //
  })
  .listen('AutobanDeleter',({autoban})=>{
    //
  })

</script>
