<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 text-start">
        <form @submit.prevent="submit" @input="removeError($event.target.getAttribute('name'))">
          <div class="mb-3">
            <label for="input-provider" class="form-label">Наименование поставщика</label>
            <input type="text"
                   name="provider"
                   v-model="provider"
                   :class="'form-control' + (getError('provider') ? ' is-invalid' : '')"
                   id="input-provider"
                   placeholder="Наименование поставщика">
            <div v-if="getError('provider')" class="invalid-feedback">
              {{ getError('provider') }}
            </div>
          </div>

          <div class="mb-3">
            <label for="input-provider-inn" class="form-label">ИНН поставщика</label>
            <input type="text"
                   name="provider_inn"
                   v-model="provider_inn"
                   :class="'form-control' + (getError('provider_inn') ? ' is-invalid' : '')"
                   id="input-provider-inn"
                   placeholder="ИНН поставщика">
            <div v-if="getError('provider_inn')" class="invalid-feedback">
              {{ getError('provider_inn') }}
            </div>
          </div>

          <div class="mb-3">
            <label for="input-provider-kpp" class="form-label">КПП поставщика</label>
            <input type="text"
                   name="provider_kpp"
                   v-model="provider_kpp"
                   :class="'form-control' + (getError('provider_kpp') ? ' is-invalid' : '')"
                   id="input-provider-kpp"
                   placeholder="КПП поставщика">
            <div v-if="getError('provider_kpp')" class="invalid-feedback">
              {{ getError('provider_kpp') }}
            </div>
          </div>

          <div class="mb-3">
            <label for="input-company-logo" class="form-label">Лого компании</label>
            <input type="file"
                   name="company_logo"
                   @change="changeFile"
                   :class="'form-control' + (getError('company_logo') ? ' is-invalid' : '')"
                   id="input-company-logo"
                   placeholder="Лого компании"
                   ref="company_logo">
            <div v-if="getError('company_logo')" class="invalid-feedback">
              {{ getError('company_logo') }}
            </div>
          </div>

          <div class="mb-3">
            <label for="input-customer-full-name" class="form-label">ФИО покупателя</label>
            <input type="text"
                   name="customer_full_name"
                   v-model="customer_full_name"
                   :class="'form-control' + (getError('customer_full_name') ? ' is-invalid' : '')"
                   id="input-customer-full-name"
                   placeholder="ФИО покупателя">
            <div v-if="getError('customer_full_name')" class="invalid-feedback">
              {{ getError('customer_full_name') }}
            </div>
          </div>

          <div class="mb-3">
            <label for="input-customer-inn" class="form-label">ИНН покупателя</label>
            <input type="text"
                   name="customer_inn"
                   v-model="customer_inn"
                   :class="'form-control' + (getError('customer_inn') ? ' is-invalid' : '')"
                   id="input-customer-inn"
                   placeholder="ИНН покупателя">
            <div v-if="getError('customer_inn')" class="invalid-feedback">
              {{ getError('customer_inn') }}
            </div>
          </div>

          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Наименование товара</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(product, key) in products">
                <td>{{ key + 1 }}</td>
                <td>
                  <input type="text"
                         :name="'products.' + key + '.name'"
                         v-model="product.name"
                         :class="'form-control' + (getError('products.' + key + '.name') ? ' is-invalid' : '')"
                         placeholder="Наименование товара">
                  <div v-if="getError('products.' + key + '.name')" class="invalid-feedback">
                    {{ getError('products.' + key + '.name') }}
                  </div>
                </td>
                <td>
                  <input type="text"
                         :name="'products.' + key + '.count'"
                         v-model="product.count"
                         :class="'form-control' + (getError('products.' + key + '.count') ? ' is-invalid' : '')"
                         placeholder="Количество">
                  <div v-if="getError('products.' + key + '.count')" class="invalid-feedback">
                    {{ getError('products.' + key + '.count') }}
                  </div>
                </td>
                <td>
                  <input type="text"
                         :name="'products.' + key + '.price'"
                         v-model="product.price"
                         :class="'form-control' + (getError('products.' + key + '.price') ? ' is-invalid' : '')"
                         placeholder="Цена">
                  <div v-if="getError('products.' + key + '.price')" class="invalid-feedback">
                    {{ getError('products.' + key + '.price') }}
                  </div>
                </td>
                <td>
                  <input type="text"
                         :value="calculateAmount(product.count, product.price)"
                         class="form-control"
                         placeholder="Сумма"
                         readonly>
                </td>
                <td>
                  <button class="btn btn-danger btn-sm" @click.prevent="removeProduct(key)">Удалить</button>
                </td>
              </tr>
            </tbody>

            <tfoot>
              <tr>
                <td colspan="5">
                  <div v-if="getError('products')" class="invalid-feedback text-end d-block">
                    {{ getError('products') }}
                  </div>
                </td>
                <td>
                  <button class="btn btn-primary btn-sm" @click.prevent="addProduct">
                    Добавить
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>

          <div class="text-center">
            <button type="submit"
                    class="btn btn-success"
                    :disabled="loading">
              {{ loading ? 'Загрузка...' : 'Скачать документ' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'App',
    data() {
      return {
        errors: {},

        provider: '',
        provider_inn: '',
        provider_kpp: '',
        company_logo: '',
        customer_full_name: '',
        customer_inn: '',
        products: [],

        loading: false,
      };
    },
    methods: {
      resetForm() {
        this.errors = {};
        this.provider = '';
        this.provider_inn = '';
        this.provider_kpp = '';
        this.company_logo = '';
        this.customer_full_name = '';
        this.customer_inn = '';
        this.products = [];
      },
      submit() {
        let formData = new FormData();

        formData.append('provider', this.provider);
        formData.append('provider_inn', this.provider_inn);
        formData.append('provider_kpp', this.provider_kpp);
        formData.append('company_logo', this.company_logo);
        formData.append('customer_full_name', this.customer_full_name);
        formData.append('customer_inn', this.customer_inn);

        this.products.forEach((product, key) => {
          formData.append('products[' + key + '][name]', product.name);
          formData.append('products[' + key + '][count]', product.count);
          formData.append('products[' + key + '][price]', product.price);
        });

        this.loading = true;

        axios({
          url: 'http://localhost:8080/api/get-document',
          method: 'POST',
          data: formData,
          responseType: 'blob',
        }).then(response => {
          this.loading = false;

          this.resetForm();
          let file = window.URL.createObjectURL(response.data);
          let a = document.createElement('a');
          a.href = file;
          a.download = 'document.pdf';
          a.click();
        }).catch(error => {
          this.loading = false;

          if (error.response.status === 422) {
            error.response.data.text().then((text) => {
              this.errors = JSON.parse(text)
            });
          } else {
            alert('Произошла непредвиденная ошибка!');
          }
        });
      },
      changeFile() {
        this.company_logo = this.$refs.company_logo.files[0];
      },
      addProduct() {
        this.products.push({
          name: '',
          count: '',
          price: '',
        })
      },
      removeProduct(key) {
        this.products.splice(key, 1);
      },
      calculateAmount(count, price) {
        let amount = count * price;

        return !isNaN(amount) ? amount : 0;
      },
      getError(key) {
        return this.errors[key] && this.errors[key][0] ? this.errors[key][0] : null;
      },
      removeError(key) {
        if (key && this.errors[key]) {
          delete this.errors[key]
        }
      },
    }
  };
</script>

<style scoped>
th, td {
  vertical-align: middle;
}
</style>
