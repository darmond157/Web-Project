<template>
    <CSmartTable header :items="items" :columns="[
                      'id',
                      'name',
                      'category',
                      'company',
                      'price',
                      'picture',
                      {
                        key: 'show_details',
                        label: 'Operations',
                        _style: { width: '1%' },
                        filter: false,
                        sorter: false
                      }
                    ]" columnFilter itemsPerPageSelect :itemsPerPage="5" columnSorter :loading="loadSpinner" pagination>
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
                    GetProductInfoFromDB()
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
      <CModalTitle>Delete Product</CModalTitle>
    </CModalHeader>
    <CModalBody>Are you sure you want to delete product?</CModalBody>
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
      <CButton color="danger" @click="DeleteProductFromDB">Delete</CButton>
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
      <CModalTitle>Edit Product with ID: {{ productInfo.id }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CForm>
        <div class="mb-3">
          <CFormLabel for="nameUpdate">Name:</CFormLabel>
          <CFormInput id="nameUpdate" :value="productInfo.name" />
        </div>
        <div class="mb-3">
          <CFormLabel for="categoryUpdate">Category:</CFormLabel>
          <CFormInput id="categoryUpdate" :value="productInfo.category" />
        </div>
        <div class="mb-3">
          <CFormLabel for="companyUpdate">Company:</CFormLabel>
          <CFormInput id="companyUpdate" :value="productInfo.company" />
        </div>
        <div class="mb-3">
          <CFormLabel for="priceUpdate">Price:</CFormLabel>
          <CFormInput id="priceUpdate" :value="productInfo.price" />
        </div>
        <div class="mb-3">
          <CFormLabel for="pictureUpdate">picture:</CFormLabel>
          <CFormInput id="pictureUpdate" :value="productInfo.picture" />
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
      <CButton color="primary" @click="EditProductOnDB">Edit</CButton>
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
        EditProductOnDB() {
            axios({
                method: 'put',
                url: `/api/products/${this.id}`,
                headers: {
                    Authorization: 'Bearer ' + localStorage.token
                },
                data: {
                    name: document.getElementById('nameUpdate').value,
                    category: document.getElementById('categoryUpdate').value,
                    company: document.getElementById('companyUpdate').value,
                    price: document.getElementById('priceUpdate').value,
                    picture: document.getElementById('pictureUpdate').value
                }
            })
            this.modalVisibilityEdit = false
            setTimeout(function() {
                location.reload()
            }, 500)
        },
        DeleteProductFromDB() {
            axios({
                method: 'delete',
                url: `/api/products/${this.id}`,
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
        GetProductInfoFromDB() {
            return axios
                .get(`/api/products/${this.id}`, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.token
                    }
                })
                .then(value => {
                    this.productInfo = value.data[0]
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
            productInfo: '',
            id: 0,
            modalVisibilityDelete: false,
            modalVisibilityEdit: false,
            modalVisibilityInfo: false,
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
            .get('/api/products/', {
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