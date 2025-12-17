import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import { buildWhatsappMessage } from '../utils/whatsapp';

export default function CheckoutPage() {
  const { items, totals, clear } = useCart();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', phone: '', type: 'delivery', address: '', payment: 'cod', notes: '' });
  const [coupon, setCoupon] = useState('');

  const submit = () => {
    if (!items.length) return alert('السلة فارغة');
    if (!form.name || !form.phone) return alert('أدخل الاسم والجوال');

    const orderNumber = `Z-${Date.now().toString().slice(-5)}`;
    const message = buildWhatsappMessage({
      orderNumber,
      name: form.name,
      phone: form.phone,
      type: form.type,
      address: form.type === 'delivery' ? form.address : 'استلام من الفرع',
      items,
      totals,
      payment: paymentLabel(form.payment)
    });

    clear();
    window.open(`https://wa.me/+201234567890?text=${message}`, '_blank');
    navigate('/');
  };

  const paymentLabel = (method) => {
    const map = { cod: 'عند الاستلام', paymob: 'Paymob', fawry: 'فوري', vodafone: 'Vodafone Cash' };
    return map[method];
  };

  return (
    <div className="container py-4 text-end">
      <h4 className="mb-3">إتمام الطلب</h4>
      <div className="row g-3">
        <div className="col-12 col-lg-8">
          <div className="glass p-3">
            <div className="row g-3">
              <div className="col-md-6">
                <label className="form-label">الاسم</label>
                <input className="form-control" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
              </div>
              <div className="col-md-6">
                <label className="form-label">الجوال</label>
                <input className="form-control" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
              </div>
              <div className="col-md-6">
                <label className="form-label">نوع الطلب</label>
                <select className="form-select" value={form.type} onChange={(e) => setForm({ ...form, type: e.target.value })}>
                  <option value="delivery">توصيل</option>
                  <option value="pickup">استلام</option>
                </select>
              </div>
              <div className="col-md-6">
                <label className="form-label">العنوان / الفرع</label>
                <input className="form-control" value={form.address} onChange={(e) => setForm({ ...form, address: e.target.value })} />
              </div>
              <div className="col-md-6">
                <label className="form-label">طريقة الدفع</label>
                <select className="form-select" value={form.payment} onChange={(e) => setForm({ ...form, payment: e.target.value })}>
                  <option value="cod">عند الاستلام</option>
                  <option value="paymob">Paymob</option>
                  <option value="fawry">فوري</option>
                  <option value="vodafone">Vodafone Cash</option>
                </select>
              </div>
              <div className="col-md-6">
                <label className="form-label">كوبون خصم</label>
                <input className="form-control" value={coupon} onChange={(e) => setCoupon(e.target.value)} />
              </div>
              <div className="col-12">
                <label className="form-label">ملاحظات</label>
                <textarea className="form-control" rows="3" value={form.notes} onChange={(e) => setForm({ ...form, notes: e.target.value })}></textarea>
              </div>
            </div>
            <button className="btn btn-primary w-100 mt-3" onClick={submit}>
              إتمام الطلب وفتح واتساب
            </button>
          </div>
        </div>
        <div className="col-12 col-lg-4">
          <div className="glass p-3">
            <div className="d-flex justify-content-between"><span>المجموع</span><strong>{totals.subtotal.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between"><span>ضريبة</span><strong>{totals.tax.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between"><span>توصيل</span><strong>{totals.delivery.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between mt-2"><span>الإجمالي</span><strong>{totals.total.toFixed(2)}</strong></div>
          </div>
        </div>
      </div>
    </div>
  );
}
