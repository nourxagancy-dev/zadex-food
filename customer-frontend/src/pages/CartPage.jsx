import { Link } from 'react-router-dom';
import { useCart } from '../context/CartContext';

export default function CartPage() {
  const { items, updateQty, remove, totals } = useCart();

  if (!items.length)
    return (
      <div className="container py-4 text-end">
        <div className="glass p-4 text-center">
          <h5>السلة فارغة</h5>
          <Link className="btn btn-primary mt-2" to="/">
            ابدأ الطلب
          </Link>
        </div>
      </div>
    );

  return (
    <div className="container py-4 text-end">
      <h4 className="mb-3">السلة</h4>
      <div className="row g-3">
        <div className="col-12 col-lg-8">
          <div className="glass p-3">
            {items.map((item) => (
              <div key={item.id} className="d-flex justify-content-between align-items-start border-bottom border-secondary pb-3 mb-3">
                <div>
                  <div className="fw-bold">{item.product.name}</div>
                  <ul className="text-muted small mb-1">
                    {Object.values(item.selections || {}).flat().map((opt) => (
                      <li key={opt.id}>{opt.name} (+{opt.price})</li>
                    ))}
                  </ul>
                  <div className="d-flex align-items-center gap-2">
                    <button className="btn btn-sm btn-outline-light" onClick={() => remove(item.id)}>
                      حذف
                    </button>
                    <input
                      type="number"
                      className="form-control form-control-sm"
                      value={item.qty}
                      min="1"
                      style={{ width: 80 }}
                      onChange={(e) => updateQty(item.id, Number(e.target.value))}
                    />
                  </div>
                </div>
                <div className="fw-bold">{item.product.price * item.qty} ج.م</div>
              </div>
            ))}
          </div>
        </div>
        <div className="col-12 col-lg-4">
          <div className="glass p-3">
            <div className="d-flex justify-content-between"><span>المجموع</span><strong>{totals.subtotal.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between"><span>ضريبة</span><strong>{totals.tax.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between"><span>توصيل</span><strong>{totals.delivery.toFixed(2)}</strong></div>
            <div className="d-flex justify-content-between mt-2"><span>الإجمالي</span><strong>{totals.total.toFixed(2)}</strong></div>
            <Link className="btn btn-primary w-100 mt-3" to="/checkout">
              إتمام الطلب
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
