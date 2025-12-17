export const categories = [
  { id: 1, name: 'برجر', slug: 'burger' },
  { id: 2, name: 'بيتزا', slug: 'pizza' },
  { id: 3, name: 'مشروبات', slug: 'drinks' }
];

export const products = [
  {
    id: 101,
    name: 'برجر كلاسيك',
    description: 'لحم بقري طازج مع جبنة شيدر وصلصة خاصة',
    price: 75,
    category_id: 1,
    image: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80',
    optionGroups: [
      {
        id: 'cheese',
        name: 'اختيار الجبنة',
        min: 1,
        max: 1,
        required: true,
        options: [
          { id: 'cheddar', name: 'شيدر', price: 0 },
          { id: 'mozzarella', name: 'موتزاريلا', price: 5 }
        ]
      },
      {
        id: 'extras',
        name: 'إضافات',
        min: 0,
        max: 3,
        required: false,
        options: [
          { id: 'jalapeno', name: 'هالبينو', price: 6 },
          { id: 'bacon', name: 'بيكون', price: 10 },
          { id: 'mushroom', name: 'مشروم', price: 7 }
        ]
      }
    ]
  },
  {
    id: 201,
    name: 'بيتزا مارجريتا',
    description: 'عجينة رفيعة مع صلصة طماطم وريحان طازج',
    price: 120,
    category_id: 2,
    image: 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=800&q=80',
    optionGroups: [
      {
        id: 'size',
        name: 'الحجم',
        min: 1,
        max: 1,
        required: true,
        options: [
          { id: 'medium', name: 'متوسط', price: 0 },
          { id: 'large', name: 'كبير', price: 25 }
        ]
      }
    ]
  }
];
