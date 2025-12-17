export const buildWhatsappMessage = ({ orderNumber, name, phone, type, address, items, totals, payment }) => {
  const lines = [];
  lines.push(`رقم الطلب: ${orderNumber}`);
  lines.push(`الاسم: ${name}`);
  lines.push(`الجوال: ${phone}`);
  lines.push(`نوع الطلب: ${type === 'delivery' ? 'توصيل' : 'استلام'}`);
  if (address) lines.push(`العنوان/الفرع: ${address}`);
  lines.push('---');
  items.forEach((item) => {
    lines.push(`${item.qty}x ${item.product.name} - ${item.product.price}ج`);
    Object.values(item.selections || {}).flat().forEach((opt) => {
      lines.push(`  • ${opt.name} (+${opt.price || 0})`);
    });
  });
  lines.push('---');
  lines.push(`Subtotal: ${totals.subtotal.toFixed(2)}`);
  lines.push(`ضريبة: ${totals.tax.toFixed(2)}`);
  lines.push(`توصيل: ${totals.delivery.toFixed(2)}`);
  lines.push(`الإجمالي: ${totals.total.toFixed(2)}`);
  lines.push(`طريقة الدفع: ${payment}`);
  return encodeURIComponent(lines.join('\n'));
};
