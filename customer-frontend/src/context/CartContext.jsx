import { createContext, useContext, useMemo, useState } from 'react';

const CartContext = createContext();

export function CartProvider({ children }) {
  const [items, setItems] = useState([]);

  const addItem = (product, selections) => {
    setItems((prev) => [...prev, { id: Date.now(), product, selections, qty: 1 }]);
  };

  const updateQty = (id, qty) => setItems((prev) => prev.map((item) => (item.id === id ? { ...item, qty } : item)));
  const remove = (id) => setItems((prev) => prev.filter((item) => item.id !== id));
  const clear = () => setItems([]);

  const totals = useMemo(() => {
    const subtotal = items.reduce((sum, item) => {
      const optsTotal = Object.values(item.selections || {}).flat().reduce((acc, opt) => acc + (opt.price || 0), 0);
      return sum + (item.product.price + optsTotal) * item.qty;
    }, 0);
    const delivery = subtotal > 0 ? 20 : 0;
    const tax = subtotal * 0.14;
    const discount = 0;
    return { subtotal, delivery, tax, discount, total: subtotal + delivery + tax - discount };
  }, [items]);

  return (
    <CartContext.Provider value={{ items, addItem, updateQty, remove, clear, totals }}>
      {children}
    </CartContext.Provider>
  );
}

export const useCart = () => useContext(CartContext);
