<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <form @submit.prevent="submit">
          <div class="mb-3">
            <label for="input-provider" class="form-label">Наименование поставщика</label>
            <input type="text" name="provider" v-model="provider" class="form-control" id="input-provider" placeholder="Наименование поставщика">
          </div>

          <div class="mb-3">
            <label for="input-provider-inn" class="form-label">ИНН поставщика</label>
            <input type="text" name="provider_inn" v-model="provider_inn" class="form-control" id="input-provider-inn" placeholder="ИНН поставщика">
          </div>

          <div class="mb-3">
            <label for="input-provider-kpp" class="form-label">КПП поставщика</label>
            <input type="text" name="provider_kpp" v-model="provider_kpp" class="form-control" id="input-provider-kpp" placeholder="КПП поставщика">
          </div>

          <div class="mb-3">
            <label for="input-company-logo" class="form-label">Лого компании</label>
            <input type="file" name="company_logo" @change="changeFile" class="form-control" id="input-company-logo" placeholder="Лого компании" ref="company_logo">
          </div>

          <div class="mb-3">
            <label for="input-customer-full-name" class="form-label">ФИО покупателя</label>
            <input type="text" name="customer_full_name" v-model="customer_full_name" class="form-control" id="input-customer-full-name" placeholder="ФИО покупателя">
          </div>

          <div class="mb-3">
            <label for="input-customer-inn" class="form-label">ИНН покупателя</label>
            <input type="text" name="customer_inn" v-model="customer_inn" class="form-control" id="input-customer-inn" placeholder="ИНН покупателя">
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
                  <input type="text" v-model="product.name" class="form-control" placeholder="Наименование товара">
                </td>
                <td>
                  <input type="text" v-model="product.count" class="form-control" placeholder="Количество">
                </td>
                <td>
                  <input type="text" v-model="product.price" class="form-control" placeholder="Цена">
                </td>
                <td>
                  <input type="text" :value="calculateAmount(product.count, product.price)" class="form-control" placeholder="Сумма" readonly>
                </td>
                <td>
                  <button class="btn btn-danger btn-sm" @click.prevent="removeProduct(key)">Удалить</button>
                </td>
              </tr>
            </tbody>

            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td>
                  <button class="btn btn-primary btn-sm" @click.prevent="addProduct">
                    Добавить
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>

          <div class="text-center">
            <button type="submit" class="btn btn-success">
              Скачать документ
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
      };
    },
    methods: {
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

        axios({
          url: 'http://test-task.loc/api/get-document',
          method: 'POST',
          data: formData,
          responseType: 'blob',
        }).then(response => {
          let file = window.URL.createObjectURL(response.data);
          let a = document.createElement('a')
          a.href = file
          a.download = 'document.pdf'
          a.click()
        }).catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data
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
        return this.errors[key][0] || null;
      }
    }
  };
</script>

<style scoped>
th, td {
  vertical-align: middle;
}
</style>
