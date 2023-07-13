<template>
    <CSmartTable header :items="items" :columns="[
              'id',
              'name',
              {
                key: 'show_details',
                label: 'Operations',
                _style: { width: '1%' },
                filter: false,
                sorter: false
              }
            ]" columnFilter itemsPerPageSelect :itemsPerPage="8" columnSorter :loading="loadSpinner" pagination>
        <template #show_details="{ item }">
              <div style="position:flex;">
                <CButton class="btn btn-outline-primary" @click="
                () => {
                  ;(modalVisibilityDelete = true), (this.id = item.id)
                }
              ">Delete
            </CButton>
      <CButton class="btn btn-outline-secondary" @click="
                () => {
                  ;(modalVisibilityEdit = true),
                    (this.id = item.id),
                    GetCategoryInfoFromDB()
                }
              ">Edit</CButton></div>
</template>
  </CSmartTable>

  <CModal
    :visible="modalVisibilityDelete"
    @close="
      () => {
        modalVisibilityDelete = false
      }
    "
  >
    <CModalHeader>
      <CModalTitle>Delete Category</CModalTitle>
    </CModalHeader>
    <CModalBody>Are you sure you want to delete category?</CModalBody>
    <CModalFooter>
      <CButton
        color="secondary"
        @click="
          () => {
            modalVisibilityDelete = false
          }
        "
        >Close</CButton
      >
      <CButton color="danger" @click="DeleteCategoryFromDB">Delete</CButton>
    </CModalFooter>
  </CModal>

  <CModal
    :visible="modalVisibilityEdit"
    @close="
      () => {
        modalVisibilityEdit = false
      }
    "
  >
    <CModalHeader>
      <CModalTitle>Edit Category with ID: {{ categoryInfo.id }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CForm>
        <div class="mb-3">
          <CFormLabel for="nameUpdate">Name:</CFormLabel>
          <CFormInput id="nameUpdate" :value="categoryInfo.name" />
        </div>
      </CForm>
    </CModalBody>
    <CModalFooter>
      <CButton
        color="secondary"
        @click="
          () => {
            modalVisibilityEdit = false
          }
        "
        >Close</CButton
      >
      <CButton color="primary" @click="EditCategoryOnDB">Edit</CButton>
    </CModalFooter>
  </CModal>

</template>

<script>
import axios from 'axios'
import { CSmartTable } from '@coreui/vue-pro'

export default {
    name: 'Dashboard',
    components: {
        CSmartTable
    },
    methods: {
        EditCategoryOnDB() {
            axios({
                method: 'put',
                url: `/api/categories/${this.id}`,
                headers: {
                    Authorization: 'Bearer ' + localStorage.token
                },
                data: {
                    name: document.getElementById('nameUpdate').value,
                }
            })
            this.modalVisibilityEdit = false
            setTimeout(function() {
                location.reload()
            }, 500)
        },
        DeleteCategoryFromDB() {
            axios({
                method: 'delete',
                url: `/api/categories/${this.id}`,
                headers: {
                    Authorization: 'Bearer ' + localStorage.token
                }
            }).then(val => {
                console.log(val)
            })
            this.modalVisibilityDelete = false
            setTimeout(function() {
                location.reload()
            }, 1000)
        },
        GetCategoryInfoFromDB() {
            return axios
                .get(`/api/categories/${this.id}`, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.token
                    }
                })
                .then(value => {
                    this.categoryInfo = value.data[0]
                })
        },
        toggleDetails(item) {
            if (this.details.includes(item._id)) {
                this.details = this.details.filter(_item => _item !== item._id)
                return
            }
            this.details.push(item._id)
        }
    },
    data() {
        return {
            items: [],
            categoryInfo: '',
            id: 0,
            modalVisibilityDelete: false,
            modalVisibilityEdit: false,
            details: [],
            loadSpinner: true
        }
    },
    beforeCreate() {
        axios.interceptors.response.use('', error => {
            if (error.response.status === 401) this.$router.push('Login')
        })
        axios.get(`/sanctum/csrf-cookie`)
    },
    created() {
        axios
            .get('/api/categories/', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.token
                }
            })
            .then(res => {
                this.items = res.data
                this.loadSpinner = false
            }).catch(()=>{})
    }
}
</script>