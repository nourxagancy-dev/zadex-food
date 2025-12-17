import { Routes, Route, Link } from 'react-router-dom';
import HomePage from './pages/HomePage';
import ProductPage from './pages/ProductPage';
import CartPage from './pages/CartPage';
import CheckoutPage from './pages/CheckoutPage';
import { CartProvider, useCart } from './context/CartContext';

function Shell({ children }) {
  const { items } = useCart();
  return (
    <div className="app bg-dusk text-light min-vh-100">
      <nav className="navbar navbar-dark bg-transparent px-3">
        <Link className="navbar-brand" to="/">
          🍽️ منيو زادكس
        </Link>
        <Link className="btn btn-outline-light" to="/cart">
          السلة ({items.length})
        </Link>
      </nav>
      {children}
    </div>
  );
}

export default function App() {
  return (
    <CartProvider>
      <Shell>
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/product/:id" element={<ProductPage />} />
          <Route path="/cart" element={<CartPage />} />
          <Route path="/checkout" element={<CheckoutPage />} />
        </Routes>
      </Shell>
    </CartProvider>
  );
}
