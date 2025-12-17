import { useMemo, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { products } from '../data/demoMenu';
import { useCart } from '../context/CartContext';

export default function ProductPage() {
  const { id } = useParams();
  const product = useMemo(() => products.find((p) => p.id === Number(id)), [id]);
  const [selections, setSelections] = useState({});
  const [error, setError] = useState('');
  const { addItem } = useCart();
  const navigate = useNavigate();

  if (!product) return <div className="container py-4 text-end">المنتج غير موجود</div>;

  const toggleOption = (groupId, option) => {
    setSelections((prev) => {
      const current = prev[groupId] || [];
      const exists = current.find((o) => o.id === option.id);
      let next;
      if (exists) {
        next = current.filter((o) => o.id !== option.id);
      } else {
        next = [...current, option];
      }
      return { ...prev, [groupId]: next };
    });
  };

  const validate = () => {
    for (const group of product.optionGroups || []) {
      const selected = selections[group.id] || [];
      if (group.required && selected.length === 0) return `${group.name}: مطلوب اختيار واحد على الأقل`;
      if (selected.length < group.min) return `${group.name}: اختر على الأقل ${group.min}`;
      if (selected.length > group.max) return `${group.name}: اختر بحد أقصى ${group.max}`;
    }
    return '';
  };

  const addToCart = () => {
    const validationError = validate();
    if (validationError) return setError(validationError);
    setError('');
    addItem(product, selections);
    navigate('/cart');
  };

  return (
    <div className="container py-4 text-end">
      <div className="row g-4">
        <div className="col-md-6">
          <div className="ratio ratio-4x3">
            <img className="rounded-4 object-fit-cover" src={product.image} alt={product.name} />
          </div>
        </div>
        <div className="col-md-6">
          <h3>{product.name}</h3>
          <p className="text-muted">{product.description}</p>
          <div className="fw-bold fs-4 mb-3">{product.price} ج.م</div>

          {(product.optionGroups || []).map((group) => (
            <div key={group.id} className="mb-3 p-3 rounded-3 glass">
              <div className="d-flex justify-content-between align-items-center">
                <h6 className="mb-0">{group.name}</h6>
                <small className="text-muted">
                  {group.required ? 'إلزامي' : 'اختياري'} • اختر {group.min} - {group.max}
                </small>
              </div>
              <div className="d-flex flex-wrap gap-2 mt-2">
                {group.options.map((opt) => {
                  const active = selections[group.id]?.some((o) => o.id === opt.id);
                  return (
                    <button
                      type="button"
                      key={opt.id}
                      className={`btn btn-sm ${active ? 'btn-primary' : 'btn-outline-light'}`}
                      onClick={() => toggleOption(group.id, opt)}
                    >
                      {opt.name} {opt.price ? `(+${opt.price})` : ''}
                    </button>
                  );
                })}
              </div>
            </div>
          ))}

          {error && <div className="alert alert-danger">{error}</div>}
          <button className="btn btn-primary w-100 py-2" onClick={addToCart}>
            إضافة للسلة
          </button>
        </div>
      </div>
    </div>
  );
}
