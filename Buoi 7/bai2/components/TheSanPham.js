// Component <the-san-pham>
const TheSanPham = {
  props: {
    ten: { type: String, required: true },
    gia: { type: Number, required: true },
    anh: { type: String, default: 'https://placehold.co/300x200?text=No+Image' },
    moTa: { type: String, default: 'Chưa có mô tả.' },
    soLuongTon: { type: Number, default: 0 }
  },
  data() {
    return { hienMoTa: false };
  },
  computed: {
    hetHang() { return this.soLuongTon <= 0; },
    giaHienThi() { return this.gia.toLocaleString('vi-VN') + ' đ'; }
  },
  methods: {
    batTatMoTa() { this.hienMoTa = !this.hienMoTa; },
    mua() { alert('Đã thêm "' + this.ten + '" vào giỏ hàng!'); }
  },
  template: `
    <div class="the">
      <img :src="anh" :alt="ten">
      <h3>{{ ten }}</h3>
      <p class="gia">{{ giaHienThi }}</p>
      <p>
        <span v-if="hetHang" class="nhan het">Hết hàng</span>
        <span v-else class="nhan con">Còn {{ soLuongTon }} sản phẩm</span>
      </p>
      <p v-if="hienMoTa" class="mo-ta">{{ moTa }}</p>
      <div class="nut">
        <button @click="batTatMoTa">{{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}</button>
        <button :disabled="hetHang" @click="mua">Mua</button>
      </div>
    </div>
  `
};