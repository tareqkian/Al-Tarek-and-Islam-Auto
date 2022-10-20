<template>
  <PageLayout>
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="nav flex-column admisetting-tabs" role="tablist" aria-orientation="vertical">
            <Loading v-if="tables.loading" />
            <a v-else
               v-for="(table,index) in tables.data"
               :key="index"
               @click="fetchTranslation(table)"
               class="nav-link d-flex justify-content-between"
               data-bs-toggle="pill" href="#pill" role="tab">
              <div>
                {{ table }}
                <!--<small class="text-muted ms-3"> {{ table }} </small>-->
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="col-xl-9">
        <div class="tab-content">
          <div class="tab-pane" id="pill" role="tabpanel">
            <div class="card">
              <div class="card-body">
                <div class="row text-center">
                  <!-- Data Table -->
                  <DataTable :loading="translation.loading"
                             :value="translation.data"
                             :filters="filters" :rows-per-page-options="[15,30,60]"
                             row-group-mode="rowspan" group-rows-by="table_name" paginator :rows="15"
                             paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                             current-page-report-template="Showing {first} to {last} of {totalRecords}"
                             table-class="table table-sm table-vcenter text-nowrap table-bordered border-bottom table-striped table-hover">
                    <template #loading>
                      <Loading />
                    </template>
                    <template #header>
                      <div class="search-element w-25 ms-auto mx-3 mb-4">
                        <input type="search" class="form-control header-search"
                               v-model="filters['global'].value" placeholder="Search…"
                               aria-label="Search" tabindex="1">
                        <i class="feather feather-search position-absolute"
                           style="top: 30%;right: 10px"></i>
                      </div>
                    </template>
<!--                    <Column :sortable="true" class="text-capitalize" field="lang" />-->

                    <Column v-for="(col,index) of translation.data[0]"
                            :field="index"
                            :header="index"
                            :key="index">
                      <template #body="val">
                        <div class="row" v-for="col in val.data[index]" :key="col.id">
                          <template v-if="col.length" v-for="(value,input) in col[0]" :key="input">
                            <div v-if="input !== 'locale' && input !== 'id'" class="col-12">
<!--                              <input class="form-control" :value="value" :placeholder="input">-->
                              <div class="form-floating my-2">
                                <input class="form-control"
                                       :value="value"
                                       :placeholder="input">
                                <label> {{ input }} </label>
                              </div>
                            </div>
                          </template>
                          <div v-else class="col">
                            opps
                          </div>
                        </div>
                      </template>
                    </Column>

                  </DataTable>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue"
import Loading from "../../components/Loading.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

import {useTranslationStore} from "../../store/Translation";
import {computed, ref} from "vue";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})


const translationStore = useTranslationStore();
const tables = computed(()=>translationStore.tables)
translationStore.initTables()

const translation = computed(()=>translationStore.translation)
const fetchTranslation = (table) => {
  translationStore.initTranslation(table)
}
</script>

<style lang="scss" scoped>
::v-deep(.p-paginator) {
  .p-paginator-current {
    margin-left: auto;
  }
}
</style>
