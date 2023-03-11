<template>
  <!--  Списки резюме по статусам. Поддерживается драг-н-дроп-->
  <div class="d-flex flex-row">
    <div
        v-for="(summaries, status) in summariesByStatus"
        :key="status"
        class="card-list d-flex flex-column p-3 pb-0 border rounded-3 mx-2"
    >
      <!-- Один список -->
      <h4 class="text-center border-bottom mb-3 pb-3">{{ summaryStatuses[status] + ' (' + summaries.length + ')' }}</h4>
      <draggable
          :list="summariesByStatus[status]"
          group="cards"
          itemKey="id"
          :sort="false"
          @end="changeStatus"
          :component-data="{status: status}"
      >
        <!-- Карточка резюме -->
        <template #item="{ element }">
          <div class="card rounded-3 mb-3">
            <img v-if="element.avatarUrl" :src="element.avatarUrl" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">
                {{ element.lastName + ' ' + element.firstName + ' ' + element.patronymic }}
              </h5>
              <p class="card-text text-sm-start">Возраст:
                {{ new Date().getFullYear() - new Date(element.birthday).getFullYear() }}</p>
              <p class="card-text">Профессия: {{ element.profession }}</p>

              <router-link :to="{name: 'summary.edit', params: {id: element.id}}">
                Изменить
              </router-link>
            </div>
          </div>
        </template>
      </draggable>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import {SUMMARY_STATUSES} from "../js/constants";
import {summaryApi} from "../js/summaryApiClient";

export default {
  name: "SummaryManage",
  components: {
    draggable
  },
  data() {
    return {
      // Для отрисовки списков
      summaryStatuses: SUMMARY_STATUSES,
      summariesByStatus: {},
    };
  },
  mounted() {
    this.loadSummariesByStatus()
  },
  methods: {
    loadSummariesByStatus() {
      for (const status of Object.keys(this.summaryStatuses)) {
        this.summariesByStatus[status] = [];
      }

      summaryApi
          .getList()
          .then((response) => {
            if (response.status === 200) {
              const summaries = response.data;
              for (const summary of summaries) {
                this.summariesByStatus[summary.status].push(summary);
              }
            } else {
              this.error = response.data.error;
              console.log(response);
            }
          });
    },
    changeStatus: function (e) {
      const newStatus = e.to.getAttribute('status');
      const summaryId = this.summariesByStatus[newStatus][e.newIndex].id;

      summaryApi
          .updateStatus(summaryId, newStatus)
          .catch(function (error) {
            // handle error
            console.log(error);
          })
    }
  }
}
</script>

<style scoped>

.card-list {
  max-width: 20rem;
  width: 20rem;
}
</style>