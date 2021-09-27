Vue.component('cms-country-default', {
	template: `<div class="container-fluid">
	<div class="row mt-2">
		<div class="col">
			<h1>Country</h1>
		</div>
		<div class="col-sm-9 text-right"></div>
	</div>
	<div v-if="countries === null" class="text-center my-5">
		<b-spinner></b-spinner>
	</div>
	<b-card v-else>
		<table class="table table-sm cms-table-no-border-top">
			<tr>
				<th width="50">Flag</th>
				<th width="50">Active</th>
				<th width="50">Code</th>
				<th width="50">ISO</th>
				<th>Name</th>
				<th>Capital</th>
				<th width="100">Currency</th>
				<th width="100">Continent</th>
			</tr>
			<tr v-for="country in countries">
				<td class="text-center">{{ country.flag }}</td>
				<td class="text-center">
					<span @click="makeActive(country.id)" style="cursor:pointer">
						<span v-if="country.active" v-b-tooltip title="Country is active.">🟢</span>
						<span v-else v-b-tooltip title="Country is hidden.">🔴</span>
					</span>
				</td>
				<td>{{ country.code }}</td>
				<td>{{ country.isoCode }}</td>
				<td>{{ country.name }}</td>
				<td>{{ country.capital }}</td>
				<td>{{ country.currency }}</td>
				<td>{{ country.continent }}</td>
			</tr>
		</table>
	</b-card>
</div>`,
	data() {
		return {
			countries: null
		}
	},
	created() {
		this.sync();
	},
	methods: {
		sync() {
			axiosApi.get('cms-country')
				.then(req => {
					this.countries = req.data.countries;
				});
		},
		makeActive(id) {
			if (!confirm('Really?')) {
				return;
			}
			axiosApi.post('cms-country/set-active', {
				id: id
			}).then(req => {
				this.sync();
			});
		}
	}
});
