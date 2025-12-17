import { useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { categories, products } from '../data/demoMenu';

export default function HomePage() {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('all');

  const filtered = useMemo(() => {
    return products.filter(
      (p) => (category === 'all' || p.category_id === Number(category)) && p.name.toLowerCase().includes(query.toLowerCase())
    );
  }, [query, category]);

  return (
    <div className="container py-4 text-end">
      <div className="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center mb-3">
        <h3 className="mb-0">المنيو</h3>
        <div className="d-flex gap-2 flex-wrap">
          <input className="form-control" placeholder="بحث" value={query} onChange={(e) => setQuery(e.target.value)} />
          <select className="form-select" value={category} onChange={(e) => setCategory(e.target.value)}>
            <option value="all">كل الأقسام</option>
            {categories.map((c) => (
              <option key={c.id} value={c.id}>
                {c.name}
              </option>
            ))}
          </select>
        </div>
      </div>
      <div className="row g-3">
        {filtered.map((p) => (
          <div className="col-6 col-md-4 col-lg-3" key={p.id}>
            <div className="glass p-3 h-100">
              <div className="ratio ratio-4x3 mb-2">
                <img className="rounded-3 object-fit-cover" src={p.image} alt={p.name} />
              </div>
              <div className="fw-bold">{p.name}</div>
              <div className="text-muted">{p.price} ج.م</div>
              <Link className="btn btn-sm btn-primary w-100 mt-2" to={`/product/${p.id}`}>
                تفاصيل
              </Link>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
